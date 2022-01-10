<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Exports\TrialBalanceExport;
use Devzone\Ams\Models\ChartOfAccount;
use Excel;

class TrialExportController
{
    public function downloadTrial()
    {
        $request = request();
        $from_date = $request['from_date'];
        $to_date = $request['to_date'];

        $export = new TrialBalanceExport($from_date, $to_date);

        return Excel::download($export,'Trial & Balance'. date('d M Y h:i A') . '.xlsx');
    }

}