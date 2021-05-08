<?php


namespace Devzone\Ams\Http\Controllers;


use Devzone\Ams\Helper\Voucher;
use Illuminate\Http\Request;

class TalhaController extends Controller
{
    public function test()
    {
        return Voucher::instance()->tempVoucher()->get();
    }
}
