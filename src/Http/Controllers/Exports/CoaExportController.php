<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Exports\CoaExport;
use Excel;
class CoaExportController
{


    public function download()
    {
        $request = request();
        $type = $request['type'];

        $export = new CoaExport($type);

        return Excel::download($export,  'COA' . date('d M Y h:i A') . '.xlsx');
    }
}