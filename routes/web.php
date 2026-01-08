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
    Route::get('accountant/chart-of-accounts/export', [\Devzone\Ams\Http\Controllers\Exports\CoaExportController::class, 'download']);
});

Route::group(['middleware' => ['permission:2.create.coa.all|2.create.coa.level5']], function () {
    Route::get('accountant/chart-of-accounts/add', function () {
        return view('ams::coa.add');
    });
});


Route::group(['middleware' => ['permission:2.coa.equity-ratio']], function () {
    Route::get('accountant/equity-ratio', function () {
        return view('ams::coa.equity-ratio');
    });
});

Route::group(['middleware' => ['permission:2.closing-fiscal-year']], function () {
    Route::get('reports/closing-fiscal-year', function () {
        return view('ams::closing.closing-fiscal-year');
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

Route::group(['middleware' => ['permission:3.trace-voucher']], function () {
    Route::get('accountant/trace-voucher', function () {
        return view('ams::journal.trace-voucher');
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
    Route::get('accountant/ledger/export', [\Devzone\Ams\Http\Controllers\Exports\LedgerExportController::class, 'download']);
});

Route::group(['middleware' => ['permission:3.trail-balance']], function () {
    Route::get('reports/trial-balance', function () {
        return view('ams::reports.trial');
    });

    Route::get('reports/trial-balance/export', [\Devzone\Ams\Http\Controllers\Exports\TrialExportController::class, 'downloadTrial']);
});

Route::group(['middleware' => ['permission:3.pnl']], function () {
    Route::get('reports/profit-and-loss', function () {
        return view('ams::reports.profit-loss');
    });
    Route::get('reports/profit-and-loss/export', [\Devzone\Ams\Http\Controllers\Exports\ProfitLossExportController::class, 'download']);

    Route::get('reports/profit-and-loss/date-wise', function () {
        return view('ams::reports.profit-loss-datewise');
    });

    Route::get('reports/profit-and-loss/date-wise/export', [\Devzone\Ams\Http\Controllers\Exports\ProfitLossDateWiseExport::class, 'download']);

});

Route::get('reports/pnl-template-manager', function () {
    return view('ams::reports.pnl-template-manager');
})->middleware('permission:4.pnl-template-manager-view');
Route::get('reports/pnl-template-report', function () {
    return view('ams::reports.pnl-template-report');
})->middleware('permission:4.pnl-template-report');
Route::group(['middleware' => ['permission:3.balance-sheet']], function () {

    Route::get('reports/balance-sheet', function () {
        return view('ams::reports.balance-sheet');//
    });

    Route::get('reports/balance-sheet/export', [\Devzone\Ams\Http\Controllers\Exports\BalanceSheetExport::class, 'download']);

});

Route::group(['middleware' => ['permission:3.day-closing']], function () {
    Route::get('reports/day-closing', function () {
        return view('ams::reports.day-closing');
    });
    Route::get('reports/day-closing/export', [\Devzone\Ams\Http\Controllers\Exports\DayClosingExportController::class, 'download']);

});
Route::group(['middleware' => ['permission:2.view.ledger']], function () {
    Route::get('journal/voucher/print/{voucher_no}/{print?}', [JournalController::class, 'printVoucher']);
});
Route::group(['middleware' => ['permission:3.view.petty-expenses']], function () {
    Route::get('petty-expenses/{id?}', function () {
        return view('ams::petty-expenses.add-petty-expenses');
    });
    Route::get('petty-expenses-list/unclaimed', function () {
        return view('ams::petty-expenses.petty-expenses-list');
    });
    Route::get('petty-expenses-list/claimed', function () {
        return view('ams::petty-expenses.claimed-petty-expenses-list');
    });
    Route::get('petty-expenses-list/approved', function () {
        return view('ams::petty-expenses.approved-petty-expenses-list');
    });
});

if(env('AMS_CUSTOMER', false) === true) {
    Route::get('ams/customer-list', function () {
        return view('ams::customers.customer-list');
    })->middleware('permission:4.view.customers');

    Route::group(['middleware' => ['permission:3.customer-payment-report']], function () {
        Route::get('reports/customer-payments', function () {
            return view('ams::reports.customer-payment-report');
        });
    });

    Route::get('ams/paid/unpaid/customer-payments', function () {
        return view('ams::customers.paid-unpaid-customer-payments');
    })->middleware('permission:4.paid-unpaid-customer-payments');
}

if(config('ams.ledger_settlement_enabled') === true) {
    Route::get('ams/ledger-allocations', function () {
        return view('ams::general-vouchers.general-voucher');
    })->middleware('auth');
}

