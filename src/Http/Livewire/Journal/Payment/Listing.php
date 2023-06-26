<?php

namespace Devzone\Ams\Http\Livewire\Journal\Payment;

use App\Models\User;
use Carbon\Carbon;
use Devzone\Ams\Helper\GeneralJournal;
use Devzone\Ams\Helper\Voucher;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\Ledger;
use Devzone\Ams\Models\LedgerAttachment;
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
    public $reversal_id;
    public $reverse_modal = false;


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
            ->when(!empty($this->nature), function ($q) {
                return $q->where('pr.nature', $this->nature);
            })
            ->when(!empty($this->status), function ($q) {
                if ($this->status == 't') {
                    return $q->whereNotNull('pr.approved_at');
                } else {
                    return $q->whereNull('pr.approved_at');
                }

            })
            ->when(!empty($this->from) && !empty($this->to), function ($q) {
                return $q->where('pr.posting_date', '>=', $this->formatDate($this->from))->where('pr.posting_date', '<=', $this->formatDate($this->to));
            })
            ->orderBy('pr.id', 'desc')
            ->paginate(20);

        return view('ams::livewire.journal.payments.listing', compact('entries'));
    }

    private function formatDate($date)
    {

        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');
    }


    public function openReverseModal($id)
    {
        $this->dispatchBrowserEvent('open-reverse-modal');
        $this->reversal_id = $id;
        $this->reverse_modal = true;
    }


    public function reverseEntry()
    {
        try {
            DB::beginTransaction();
            if (!auth()->user()->can('2.payments.reversal')) {
                throw new \Exception(env('PERMISSION_ERROR'));
            }
            $payment = PaymentReceiving::find($this->reversal_id);
            if (empty($payment)) {
                throw new \Exception('Record not found.');
            }
            if ($payment['reversal'] == 't') {
                throw new \Exception('You have already posted entry as reversal.');
            }
            if (empty($payment['voucher_no'])) {
                throw new \Exception('Voucher no not found.');
            }

            $entries = Ledger::where('voucher_no', $payment['voucher_no'])->get();
            $vno = Voucher::instance()->voucher()->get();
            foreach ($entries as $e) {

                $data = $e->toArray();
                $debit = $data['debit'];
                $credit = $data['credit'];

                unset($data['id'], $data['created_at'], $data['updated_at']);
                $data['approved_at'] = date('Y-m-d H:i:s');
                $data['description'] = "REVERSAL ENTRY AGAINST VOUCHER # " . $data['voucher_no'] . " " . $data['description'];
                $data['debit'] = $credit;
                $data['credit'] = $debit;
                $data['voucher_no'] = $vno;
                Ledger::create($data);

            }

            $payment->update([
                'reversal' => 't'
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('close-reverse-modal');
        } catch (\Exception $e) {

            $this->addError('status', $e->getMessage());
            DB::rollBack();
        }

    }

    public function resetSearch()
    {
        $this->reset(['status', 'from', 'to', 'nature']);
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

            if (!auth()->user()->can('2.payments.approve')) {
                throw new \Exception(env('PERMISSION_ERROR'));
            }
            $payment = PaymentReceiving::find($id);

            if (!empty($payment['approved_at'])) {
                throw new \Exception('Transaction already has been approved.');
            }
            $vno = Voucher::instance()->voucher()->get();

            $description = $payment->description;
            $created = User::find($payment['added_by']);

            if ($payment['nature'] == 'transfer_entry') {
                $description = ' Amount "PKR ' . $payment['amount'] . '" transferred from "'
                    . ChartOfAccount::find($payment['second_account_id'])->name . '" to "'
                    . ChartOfAccount::find($payment['first_account_id'])->name . '" with description "'
                    . $payment->description . '".';
            }

            $description .= " Created by " . $created->name . " @ " . date('d M, Y h:i A', strtotime($payment['created_at']));
            $description .= ". Approved by " . Auth::user()->name . " @ " . date('d M, Y h:i A');

            if (auth()->user()->can('2.create.transfer.restricted-date')) {
                if (Carbon::now()->toDateString() > $payment['posting_date']) {
                    $diff_in_days = Carbon::parse($payment['posting_date'])->diffInDays(Carbon::now());

                    if (empty(env("AMS_RESTRICT_DATE"))) {
                        $restrict_days = 3;
                    } else {
                        $restrict_days = env("AMS_RESTRICT_DATE");
                    }

                    if ($diff_in_days > $restrict_days) {
                        throw new \Exception("You can't approve the record after " . ($restrict_days == 1 ? $restrict_days . " day" : $restrict_days . " days") . " of transaction  date.");
                    }
                }
            }


            if ($payment['nature'] == 'receive') {
                GeneralJournal::instance()->account($payment['first_account_id'])
                    ->credit($payment['amount'])->voucherNo($vno)->reference('payment')
                    ->date($payment['posting_date'])->approve()->description($description)->execute();

                GeneralJournal::instance()->account($payment['second_account_id'])
                    ->debit($payment['amount'])->voucherNo($vno)->reference('receiving')
                    ->date($payment['posting_date'])->approve()->description($description)->execute();
            }

            if ($payment['nature'] == 'pay') {
                GeneralJournal::instance()->account($payment['first_account_id'])
                    ->debit($payment['amount'])->voucherNo($vno)->reference('receiving')
                    ->date($payment['posting_date'])->approve()->description($description)->execute();

                GeneralJournal::instance()->account($payment['second_account_id'])
                    ->credit($payment['amount'])->voucherNo($vno)->reference('payment')
                    ->date($payment['posting_date'])->approve()->description($description)->execute();
            }

            if ($payment['nature'] == 'transfer_entry') {
                GeneralJournal::instance()->account($payment['first_account_id'])
                    ->credit($payment['amount'])->voucherNo($vno)->reference('Transfer Entry')
                    ->date($payment['posting_date'])->approve()->description($description)->execute();

                GeneralJournal::instance()->account($payment['second_account_id'])
                    ->debit($payment['amount'])->voucherNo($vno)->reference('Transfer Entry')
                    ->date($payment['posting_date'])->approve()->description($description)->execute();
            }

            $payment->update([
                'approved_by' => Auth::user()->id,
                'approved_at' => date('Y-m-d H:i:s'),
                'voucher_no' => $vno
            ]);
            if (!empty($payment['attachment'])) {
                LedgerAttachment::create([
                    'account_id' => null,
                    'voucher_no' => $vno,
                    'type' => 1,
                    'attachment' => $payment['attachment']
                ]);
            }

            $this->success = 'Entry has been approved.';
            DB::commit();
        } catch
        (\Exception $e) {
            DB::rollBack();
            $this->addError('success', $e->getMessage());
        }
    }
}
