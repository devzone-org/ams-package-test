<?php


namespace Devzone\Ams\Http\Controllers;


use Illuminate\Http\Request;

class TalhaController extends Controller
{
    public function test(Request $request)
    {
        return view('ams::welcome');
    }
}
