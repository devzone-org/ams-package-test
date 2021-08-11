<?php


namespace Devzone\Ams\Http\Controllers;


use Devzone\Ams\Models\Ledger;

class JournalController extends Controller
{
    public function printVoucher($voucher_no,$print=false)
    {
        $ledger = Ledger::from('ledgers as l')
            ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
            ->join('users as p','l.posted_by','=','p.id')
            ->join('users as a','l.approved_by','=','a.id')
            ->where('l.is_approve', 't')
            ->where('l.voucher_no', $voucher_no)
            ->select('l.*', 'coa.name', 'coa.code','p.name as posted','a.name as approved')
            ->orderBy('id','asc')
            ->get();



        return view('ams::journal.print-voucher', compact('ledger','print'));
    }
}
