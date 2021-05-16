<?php


use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('ams::dashboard');
});

Route::get('chart-of-accounts', function () {
    return view('ams::coa.list');
});

Route::get('chart-of-accounts/add', function () {
    return view('ams::coa.add');
});

Route::get('journal', function () {
    return view('ams::journal.temp-list');
});

Route::get('journal/add', function () {
    return view('ams::journal.add');
});
Route::get('journal/edit/{voucher_no}', function ($voucher_no) {
    return view('ams::journal.edit', compact('voucher_no'));
});

Route::get('reports', function () {
    return view('ams::reports.home');
});

Route::get('reports/ledger',function(){
    return view('ams::reports.ledger');
});

