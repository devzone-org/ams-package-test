<?php


use Devzone\Ams\Http\Controllers\TalhaController;
use Illuminate\Support\Facades\Route;


Route::get('/',function (){
    return view('ams::welcome');
});
