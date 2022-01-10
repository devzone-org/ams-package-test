<?php

namespace Devzone\Ams\Http\Controllers\Exports;


use Devzone\Ams\Exports\PnlDatewise;
use Excel;

class PnlDatewiseController
{

    public function download()
    {
        $request = request();
        $from_date = $request['from_date'];
        $to_date = $request['to_date'];

        $export = new PnlDatewise( $from_date, $to_date);

        return Excel::download($export, 'P & L Datewise' . date('d M Y h:i A') . '.xlsx');
    }
}