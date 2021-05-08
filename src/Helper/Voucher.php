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
}
