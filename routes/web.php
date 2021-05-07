<?php


use Illuminate\Support\Facades\Route;


Route::get('/',function (){
    return view('ams::dashboard');
});

Route::get('chart-of-accounts',function (){
    return view('ams::coa.list');
});
