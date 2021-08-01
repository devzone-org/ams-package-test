<?php

namespace Devzone\Ams\Http\Livewire\Journal\Payment;

use App\Models\User;
use Devzone\Ams\Helper\GeneralJournal;
use Devzone\Ams\Helper\Voucher;
use Devzone\Ams\Models\PaymentReceiving;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Listing extends Component
{

    public $success;

    public function render()
    {
        $entries = PaymentReceiving::from('payments_receiving as pr')
            ->join('chart_of_accounts as f', 'f.id', '=', 'pr.first_account_id')
            ->join('chart_of_accounts as s', 's.id', '=', 'pr.second_account_id')
            ->join('users as u', 'u.id', '=', 'pr.added_by')
            ->leftJoin('users as a', 'a.id', '=', 'pr.approved_by')
            ->select(
                'pr.*', 'f.name as first_account_name', 's.name as second_account_name', 'u.name as added_by', 'a.name as approved_by_name'
            )->get();

        return view('ams::livewire.journal.payments.listing', compact('entries'));
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
            $description .= " Created by ".$created->name." @ ".date('d M, Y h:i A',strtotime($payment['created_at']));
            $description .= ". Approved by ".Auth::user()->name." @ ".date('d M, Y h:i A');
            if($payment['nature'] == 'receive'){
                GeneralJournal::instance()->account($payment['first_account_id'])
                    ->credit($payment['amount'])->voucherNo($vno)
                    ->date(date('Y-m-d'))->approve()->description($description)->execute();

                GeneralJournal::instance()->account($payment['second_account_id'])
                    ->debit($payment['amount'])->voucherNo($vno)
                    ->date(date('Y-m-d'))->approve()->description($description)->execute();
            }

            if($payment['nature'] == 'pay'){
                GeneralJournal::instance()->account($payment['first_account_id'])
                    ->debit($payment['amount'])->voucherNo($vno)
                    ->date(date('Y-m-d'))->approve()->description($description)->execute();

                GeneralJournal::instance()->account($payment['second_account_id'])
                    ->credit($payment['amount'])->voucherNo($vno)
                    ->date(date('Y-m-d'))->approve()->description($description)->execute();
            }

            $payment -> update([
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
