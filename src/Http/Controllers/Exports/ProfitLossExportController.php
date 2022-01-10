<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Exports\ProfitLossExport;
use Excel;

class ProfitLossExportController
{

    public function download()
    {
        $request = request();
        $from_date = $request['from_date'];
        $to_date = $request['to_date'];

        $export = new ProfitLossExport( $from_date, $to_date);

        return Excel::download($export,'P&L'. date('d M Y h:i A') . '.xlsx');
    }

}