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

    protected $type;

    public function __construct()
    {
        $request = request();
        $this->type = $request['type'];
    }

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
            ->get();


        $data = [];
        foreach ($sth->where('level', 1) as $one) {


            foreach($sth->where('sub_account',$one->id) as $two){
                foreach($sth->where('sub_account',$two->id) as $three){
                    foreach($sth->where('sub_account',$three->id) as $four){
                        foreach($sth->where('sub_account',$four->id) as $five){

                            $clo =  $this->closingBalance($five['nature'], $five['is_contra'], $five['debit'], $five['credit']);
                            if ($clo < 0) {
                                $bal= number_format($clo, 2 );
                            } else {
                                $bal = number_format($clo, 2);
                            }

                            $data [] = [
                                'name' => $five['name'],
                                'code' => str_pad($five['code'],7,"0",STR_PAD_LEFT),
                                'balance' => $bal,
                                'date' => !empty($five['posting_date']) ? date('d M, Y',strtotime($five['posting_date'])) : ''

                            ];

                        }
                    }
                }
            }
        }

//        dd($data);

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