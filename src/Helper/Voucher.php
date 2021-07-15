<?php


namespace Devzone\Ams\Helper;


use Illuminate\Support\Facades\DB;

class Voucher
{
    private $name;

    public static function instance()
    {
        $voucher = new Voucher();
        return $voucher;
    }

    public function tempVoucher()
    {
        $this->name = 'temp_voucher';
        return $this;
    }

    public function tempVoucherOnly()
    {
        $this->name = 'temp_voucher';
        $voucher = \Devzone\Ams\Models\Voucher::where('name', $this->name)->get();
        $voucher = $voucher->first();
        $count = $voucher->value;
        return $count + 1;
    }

    public function voucher()
    {
        $this->name = 'voucher';
        return $this;
    }

    public function coa()
    {
        $this->name = 'coa';
        return $this;
    }
    public function advances()
    {
        $this->name = 'advances_receipt';
        return $this;
    }

    public function get()
    {
        $voucher = \Devzone\Ams\Models\Voucher::where('name', $this->name)->get();
        $voucher = $voucher->first();
        $count = $voucher->value;
        $count = $count + 1;
        DB::table('vouchers')
            ->where('id', $voucher->id)
            ->update([
                'value' => DB::raw('value + 1')
            ]);
        return $count;
    }

    public function updateCounter()
    {
        DB::table('vouchers')
            ->where('name', $this->name)
            ->update([
                'value' => DB::raw('value + 1')
            ]);

        return \Devzone\Ams\Models\Voucher::where('name', $this->name)->first()->value + 1;
    }
}
