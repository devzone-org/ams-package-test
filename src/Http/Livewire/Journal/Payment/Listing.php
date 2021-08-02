<?php

namespace Devzone\Ams\Http\Livewire\Journal\Payment;

use App\Models\User;
use Devzone\Ams\Helper\GeneralJournal;
use Devzone\Ams\Helper\Voucher;
use Devzone\Ams\Models\PaymentReceiving;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{

    use WithPagination;

    public $success;
    public $nature;
    public $from;
    public $to;
    public $status;


    public function render()
    {
        $entries = PaymentReceiving::from('payments_receiving as pr')
            ->join('chart_of_accounts as f', 'f.id', '=', 'pr.first_account_id')
            ->join('chart_of_accounts as s', 's.id', '=', 'pr.second_account_id')
            ->join('users as u', 'u.id', '=', 'pr.added_by')
            ->leftJoin('users as a', 'a.id', '=', 'pr.approved_by')
            ->select(
                'pr.*', 'f.name as first_account_name', 's.name as second_account_name', 'u.name as added_by', 'a.name as approved_by_name'
            )
            ->when(!empty($this->nature),function($q){
                return $q->where('pr.nature',$this->nature);
            })
            ->when(!empty($this->status),function($q){
                if($this->status=='t'){
                    return $q->whereNotNull('pr.approved_at');
                } else {
                    return $q->whereNull('pr.approved_at');
                }

            })
            ->when(!empty($this->from) && !empty($this->to) ,function($q){
                return $q->where('pr.posting_date','>=',$this->from)->where('pr.posting_date','<=',$this->to);
            })
            ->orderBy('pr.id', 'desc')
            ->paginate(20);

        return view('ams::livewire.journal.payments.listing', compact('entries'));
    }

    public function search(){

    }

    public function resetSearch(){
        $this->reset(['status','from','to','nature']);
    }


    public function delete($id)
    {
        $payment = PaymentReceiving::find($id);
        if (empty($payment['approved_at'])) {
            $payment->delete();
            $this->success = 'Record has been deleted.';
        } else {
            $this->addError('success', 'Record has already been approved so unable to delete.');
        }
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $payment = PaymentReceiving::find($id);

            if (!empty($payment['approved_at'])) {
                throw new \Exception('Transaction already has been approved.');
            }
            $vno = Voucher::instance()->voucher()->get();

            $description = $payment->description;
            $created = User::find($payment['added_by']);
            $description .= " Created by " . $created->name . " @ " . date('d M, Y h:i A', strtotime($payment['created_at']));
            $description .= ". Approved by " . Auth::user()->name . " @ " . date('d M, Y h:i A');
            if ($payment['nature'] == 'receive') {
                GeneralJournal::instance()->account($payment['first_account_id'])
                    ->credit($payment['amount'])->voucherNo($vno)
                    ->date(date('Y-m-d'))->approve()->description($description)->execute();

                GeneralJournal::instance()->account($payment['second_account_id'])
                    ->debit($payment['amount'])->voucherNo($vno)
                    ->date(date('Y-m-d'))->approve()->description($description)->execute();
            }

            if ($payment['nature'] == 'pay') {
                GeneralJournal::instance()->account($payment['first_account_id'])
                    ->debit($payment['amount'])->voucherNo($vno)
                    ->date(date('Y-m-d'))->approve()->description($description)->execute();

                GeneralJournal::instance()->account($payment['second_account_id'])
                    ->credit($payment['amount'])->voucherNo($vno)
                    ->date(date('Y-m-d'))->approve()->description($description)->execute();
            }

            $payment->update([
                'approved_by' => Auth::user()->id,
                'approved_at' => date('Y-m-d H:i:s'),
                'voucher_no' => $vno
            ]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('success', $e->getMessage());
        }
    }
}
