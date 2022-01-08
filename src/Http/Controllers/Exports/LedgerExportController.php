<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Exports\LedgerExport;
use Devzone\Ams\Models\ChartOfAccount;
use Excel;

class LedgerExportController
{

    public function download()
    {
        $request = request();
        $account_id = $request['id'];
        $coa = ChartOfAccount::find($request['id']);
        $from_date = $request['from_date'];
        $to_date = $request['to_date'];

        $export = new LedgerExport($account_id, $from_date, $to_date);

        return Excel::download($export, 'GL ' . $coa['name'] . ' - ' . date('d M Y h:i A') . '.xlsx');
    }
}