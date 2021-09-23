<?php


namespace Devzone\Ams\Helper;


class ChartOfAccount
{
    private $opening_balance;

    public static function instance()
    {
        $coa = new ChartOfAccount();
        return $coa;
    }

    public function setOpeningBalance($balance)
    {
        $this->opening_balance = $balance;
        return $this;
    }

    public function createSupplierAccount($name)
    {

        $code = Voucher::instance()->coa()->get();
        $code = str_pad($code, 7, "0", STR_PAD_LEFT);
        $parent = \Devzone\Ams\Models\ChartOfAccount::where('reference', 'vendor-payable-4')->first();
        if (empty($parent)) {
            throw new \Exception('Parent account not found.');
        }
        $account_id = \Devzone\Ams\Models\ChartOfAccount::create([
            'name' => $name,
            'type' => $parent->type,
            'sub_account' => $parent->id,
            'level' => 5,
            'code' => $code,
            'nature' => $parent->nature,
            'status' => 't'
        ])->id;

        if (!empty($this->opening_balance)) {
            $voucher_no = Voucher::instance()->voucher()->get();
            $entry = GeneralJournal::instance()->account($account_id);
            if ($this->opening_balance > 0) {
                $entry = $entry->credit(abs($this->opening_balance));
            } else {
                $entry = $entry->debit(abs($this->opening_balance));
            }

            $entry->description('Opening balance')->voucherNo($voucher_no)
                ->date(date('Y-m-d'))->approve()->execute();
        }

        return $account_id;


    }

    public function createCustomerAccount($name)
    {

        $code = Voucher::instance()->coa()->get();
        $code = str_pad($code, 7, "0", STR_PAD_LEFT);
        $parent = \Devzone\Ams\Models\ChartOfAccount::where('reference', 'customer-receivable-4')->first();
        if (empty($parent)) {
            throw new \Exception('Parent account not found.');
        }
        $account_id = \Devzone\Ams\Models\ChartOfAccount::create([
            'name' => $name,
            'type' => $parent->type,
            'sub_account' => $parent->id,
            'level' => 5,
            'code' => $code,
            'nature' => $parent->nature,
            'status' => 't'
        ])->id;



        return $account_id;


    }
}
