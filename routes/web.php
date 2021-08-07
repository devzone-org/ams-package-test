<?php


use Devzone\Ams\Http\Controllers\JournalController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:2.dashboard']], function () {
    Route::get('/', function () {
        return view('ams::dashboard');
    });
});

Route::group(['middleware' => ['permission:2.coa.view']], function () {
    Route::get('accountant/chart-of-accounts', function () {
        return view('ams::coa.list');
    });
});

Route::group(['middleware' => ['permission:2.create.coa.all|2.create.coa.level5']], function () {
    Route::get('accountant/chart-of-accounts/add', function () {
        return view('ams::coa.add');
    });
});

Route::group(['middleware' => ['permission:2.create.transfer.any-date|2.create.transfer.restricted-date']], function () {
    Route::get('accountant/journal', function () {
        return view('ams::journal.temp-list');
    });
});
Route::group(['middleware' => ['permission:2.create.transfer.any-date|2.create.transfer.restricted-date']], function () {
    Route::get('accountant/journal/add', function () {
        return view('ams::journal.add');
    });
});

Route::group(['middleware' => ['permission:2.day.closing']], function () {
    Route::get('accountant/day-close', function () {
        return view('ams::journal.close');
    });
});

Route::group(['middleware' => ['permission:2.payments.any|2.payments.own']], function () {
    Route::get('accountant/payments', function () {
        return view('ams::journal.payments.list');
    });
});

Route::group(['middleware' => ['permission:2.payments.any|2.payments.own']], function () {
    Route::get('accountant/payments/add', function () {
        return view('ams::journal.payments.add');
    });
});

Route::group(['middleware' => ['permission:2.edit.transfer.unapproved']], function () {
    Route::get('accountant/journal/edit/{voucher_no}', function ($voucher_no) {
        return view('ams::journal.edit', compact('voucher_no'));
    });
});

Route::get('reports', function () {
    return view('ams::reports.home');
});

Route::group(['middleware' => ['permission:2.view.ledger']], function () {
    Route::get('accountant/ledger', function () {
        return view('ams::reports.ledger');
    });
});

Route::group(['middleware' => ['permission:3.trail-balance']], function () {
    Route::get('reports/trial-balance', function () {
        return view('ams::reports.trial');
    });
});

Route::group(['middleware' => ['permission:3.pnl']], function () {
    Route::get('reports/profit-and-loss', function () {
        return view('ams::reports.profit-loss');
    });
});
Route::group(['middleware' => ['permission:3.day-closing']], function () {
    Route::get('reports/day-closing', function () {
        return view('ams::reports.day-closing');
    });
});
Route::group(['middleware' => ['permission:2.view.ledger']], function () {
    Route::get('journal/voucher/print/{voucher_no}/{print?}', [JournalController::class, 'printVoucher']);

});
