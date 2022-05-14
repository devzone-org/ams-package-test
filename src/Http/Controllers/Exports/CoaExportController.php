<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Exports\CoaExport;
use Devzone\Ams\Models\ChartOfAccount;
use Excel;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use SplTempFileObject;

class CoaExportController
{
    public function download()
    {

        $sth = ChartOfAccount::from('chart_of_accounts as coa')
            ->leftJoin('ledgers as l', function ($q) {
                return $q->on('l.account_id', '=', 'coa.id')->where('l.is_approve', 't');
            })
            ->when(!empty($this->type), function ($q) {
                return $q->where('coa.type', $this->type);
            })->select('coa.*', DB::raw('SUM(l.debit) as debit'), DB::raw('SUM(l.credit) as credit'),
                DB::raw('max(l.posting_date) as posting_date'))
            ->groupBy('coa.id')
            ->orderByRaw('FIELD(coa.type,"Assets","Liabilities","Equity","Income","Expenses")')
            ->get()->toArray();


        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne(['id', 'name', 'type', 'sub_account', 'level', 'code', 'nature','status' ,'is_contra', 'reference', 'created_at', 'updated_at', 'debit', 'credit','posting_date']);

        $csv->insertAll($sth);

        $csv->output('COA' . date('d M Y h:i A') . '.csv');



//        $request = request();
//        $type = $request['type'];
//
//        $export = new CoaExport($type);
//
//        return Excel::download($export,  'COA' . date('d M Y h:i A') . '.xlsx');
    }
}