<?php


use Devzone\Ams\Http\Controllers\JournalController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('ams::dashboard');
});

Route::get('accountant/chart-of-accounts', function () {
    return view('ams::coa.list');
});

Route::get('accountant/chart-of-accounts/add', function () {
    return view('ams::coa.add');
});

Route::get('accountant/journal', function () {
    return view('ams::journal.temp-list');
});

Route::get('accountant/journal/add', function () {
    return view('ams::journal.add');
});

Route::get('accountant/day-close', function () {
    return view('ams::journal.close');
});

Route::get('accountant/journal/edit/{voucher_no}', function ($voucher_no) {
    return view('ams::journal.edit', compact('voucher_no'));
});

Route::get('reports', function () {
    return view('ams::reports.home');
});

Route::get('accountant/ledger',function(){
    return view('ams::reports.ledger');
});



Route::get('reports/trial-balance',function(){
    return view('ams::reports.trial');
});

Route::get('reports/profit-and-loss',function (){
    return view('ams::reports.profit-loss');
});

Route::get('journal/voucher/print/{voucher_no}/{print?}',[JournalController::class,'printVoucher']);

