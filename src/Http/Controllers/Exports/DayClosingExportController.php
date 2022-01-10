<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Exports\DayClosingExport;

use Excel;
class DayClosingExportController
{
    public function download()
    {
        $request = request();
        $user_account_id = $request['id'];
        $from_date = $request['from_date'];
        $to_date = $request['to_date'];

        $export = new DayClosingExport($user_account_id, $from_date, $to_date);

        return Excel::download($export,  'Day CLosing' . date('d M Y h:i A') . '.xlsx');
    }
}