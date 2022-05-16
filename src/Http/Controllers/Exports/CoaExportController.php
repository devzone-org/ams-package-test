<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Exports\CoaExport;
use Devzone\Ams\Models\ChartOfAccount;
use Excel;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use SplTempFileObject;
use function Couchbase\defaultDecoder;

class CoaExportController
{

    private function closingBalance($nature, $is_contra, $debit = 0, $credit = 0)
    {
        if ($nature == 'd') {
            if ($is_contra == 'f') {
                $balance = $debit - $credit;
            } else {
                $balance = $credit - $debit;
            }
        } else {
            if ($is_contra == 'f') {
                $balance = $credit - $debit;
            } else {
                $balance = $debit - $credit;
            }
        }
        return $balance;
    }

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


        $data = [];
        foreach ($sth as $s) {

//            dd($s);
            $clo =  $this->closingBalance($s['nature'], $s['is_contra'], $s['debit'], $s['credit']);
            if ($clo < 0) {
                $bal= number_format(-$clo, 2 );
            } else {
               $bal = number_format($clo, 2);
            }

            $data [] = [
                'name' => $s['name'],
                'code' => $s['code'],
                'balance' => $bal,
                'date' => !empty($s['posting_date']) ? date('d M, Y',strtotime($s['posting_date'])) : '',
            ];

        }


        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne(['Name', 'Code', 'Balance', 'Date',]);

        $csv->insertAll($data);

        $csv->output('COA' . date('d M Y h:i A') . '.csv');


//        $request = request();
//        $type = $request['type'];
//
//        $export = new CoaExport($type);
//
//        return Excel::download($export,  'COA' . date('d M Y h:i A') . '.xlsx');
    }
}