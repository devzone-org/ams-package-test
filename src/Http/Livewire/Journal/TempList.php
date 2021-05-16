<?php


namespace Devzone\Ams\Http\Livewire\Journal;


use Devzone\Ams\Helper\Voucher;
use Devzone\Ams\Models\Ledger;
use Devzone\Ams\Models\LedgerAttachment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TempList extends Component
{

    public $success;

    public function render()
    {
        $temp_list = Ledger::from('ledgers as l')
            ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
            ->join('users as u', 'u.id', '=', 'l.posted_by')
            ->where('l.is_approve', 'f')
            ->select('l.*', 'coa.name', 'coa.code', 'u.name as posting')
            ->orderBy('l.voucher_no')->get();
        return view('ams::livewire.journal.temp-list', compact('temp_list'));
    }

    public function deleteTempEntry($voucher_no)
    {
        Ledger::where('is_approve', 'f')->where('voucher_no', $voucher_no)->delete();
        LedgerAttachment::where('type', '0')->where('voucher_no', $voucher_no)->delete();
        $this->success = 'Voucher #' . $voucher_no . ' has been deleted.';
    }

    public function approveTempEntry($voucher_no)
    {
        $vno = Voucher::instance()->voucher()->get();
        LedgerAttachment::where('type', '0')->where('voucher_no', $voucher_no)->update([
            'type' => '1',
            'voucher_no' => $vno
        ]);
        Ledger::where('is_approve', 'f')->where('voucher_no', $voucher_no)->update([
            'voucher_no' => $vno,
            'is_approve' => 't',
            'approved_at' => date('Y-m-d H:i:s'),
            'approved_by' => Auth::user()->id
        ]);
    }
}
