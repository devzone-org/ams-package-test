<?php


namespace Devzone\Ams\Helper;


use Devzone\Ams\Models\Ledger;
use Illuminate\Support\Facades\Auth;

class GeneralJournal
{
    private $account_id;
    private $debit;
    private $credit;
    private $description;
    private $posting_date;
    private $voucher_no;
    private $approved_at;
    private $approved_by;
    private $is_approve = 'f';


    public static function instance()
    {
        $ledger = new GeneralJournal();
        return $ledger;
    }

    public function account($id)
    {
        $this->account_id = $id;
        return $this;
    }

    public function debit($amount)
    {
        $this->debit = $amount;
        $this->credit = 0;
        return $this;
    }

    public function credit($amount)
    {
        $this->credit = $amount;
        $this->debit = 0;
        return $this;
    }

    public function description($text)
    {
        $this->description = $text;
        return $this;
    }

    public function date($date)
    {
        $this->posting_date = $date;
        return $this;
    }

    public function voucherNo($voucher_no)
    {
        $this->voucher_no = $voucher_no;
        return $this;
    }

    public function approve()
    {
        $this->approved_at = date('Y-m-d H:i:s');
        $this->approved_by = Auth::user()->id;
        $this->is_approve = 't';
        return $this;
    }


    public function execute()
    {
        Ledger::create([
            'account_id' => $this->account_id,
            'voucher_no' => $this->voucher_no,
            'debit' => $this->debit,
            'credit' => $this->credit,
            'description' => $this->description,
            'posting_date' => $this->posting_date,
            'posted_by' => Auth::user()->id,
            'approved_at' => $this->approved_at ?? null,
            'approved_by' => $this->approved_by ?? null,
            'is_approve' => $this->is_approve,
        ]);

    }

}
