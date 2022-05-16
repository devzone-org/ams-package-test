<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Exports\TrialBalanceExport;
use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use SplTempFileObject;
use Excel;

class TrialExportController
{

    protected $from_date;
    protected $to_date;

    public function __construct()
    {
        $request = request();
        $this->from_date = $request['from_date'];
        $this->to_date = $request['to_date'];
    }

    private function formatDate($date)
    {

        return \Carbon\Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');

    }

    public function downloadTrial()
    {
        $ledger = [];
        if (!empty($this->from_date) && !empty($this->to_date)) {
            $pnl = \Devzone\Ams\Models\Ledger::from('ledgers as l')
                ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
                ->where('l.is_approve', 't')
                ->where('l.posting_date', '>=', $this->formatDate($this->from_date))
                ->where('l.posting_date', '<=', $this->formatDate($this->to_date))
                ->whereIn('coa.type', ['Expenses', 'Income'])
                ->select(DB::raw('sum(debit) as debit'), DB::raw('sum(credit) as credit'), 'coa.type', 'coa.name', 'coa.code', 'coa.nature', 'coa.is_contra')
                ->groupBy('l.account_id')
                ->orderByRaw('FIELD(coa.type,"Income","Expenses")')
                ->get();

            $balance = \Devzone\Ams\Models\Ledger::from('ledgers as l')
                ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
                ->where('l.is_approve', 't')
                ->where('l.posting_date', '<=', $this->formatDate($this->to_date))
                ->whereIn('coa.type', ['Assets', 'Liabilities', 'Equity'])
                ->select(DB::raw('sum(debit) as debit'), DB::raw('sum(credit) as credit'), 'coa.type', 'coa.name', 'coa.code', 'coa.nature', 'coa.is_contra')
                ->groupBy('l.account_id')
                ->orderByRaw('FIELD(coa.type,"Assets","Liabilities","Equity")')
                ->get();


            foreach ($balance as $pl) {
                $debit = $credit = 0;
                if ($pl->nature == 'd') {
                    if ($pl->is_contra == 'f') {
                        $debit = $pl->debit - $pl->credit;
                    } else {
                        $credit = $pl->credit - $pl->debit;
                    }
                }
                if ($pl->nature == 'c') {
                    if ($pl->is_contra == 'f') {
                        $credit = $pl->credit - $pl->debit;
                    } else {
                        $debit = $pl->debit - $pl->credit;
                    }
                }
                if (empty($debit) && empty($credit)) {
                    continue;
                }
                $ledger[] = ['type' => $pl['type'], 'code' => $pl['code'], 'account_name' => $pl['name'], 'debit' => $debit, 'credit' => $credit];
            }


            foreach ($pnl as $pl) {
                $debit = $credit = 0;
                if ($pl->nature == 'd') {
                    if ($pl->is_contra == 'f') {
                        $debit = $pl->debit - $pl->credit;
                    } else {
                        $credit = $pl->credit - $pl->debit;
                    }
                }
                if ($pl->nature == 'c') {
                    if ($pl->is_contra == 'f') {
                        $credit = $pl->credit - $pl->debit;
                    } else {
                        $debit = $pl->debit - $pl->credit;
                    }
                }
                if (empty($debit) && empty($credit)) {
                    continue;
                }
                $ledger[] = ['type' => $pl['type'], 'code' => $pl['code'], 'account_name' => $pl['name'], 'debit' => $debit, 'credit' => $credit];
            }
        }

        foreach ($ledger as $l) {

            $data[] = [
                'type' => $l['type'],
                'account_name' => $l['code'] . '-' . $l['account_name'],
                'debit' => $l['debit'] >= 0 ? number_format($l['debit'], 2) : number_format(-$l['debit'], 2),
                'credit' => $l['credit'] >= 0 ? number_format($l['credit'], 2) : number_format(-$l['credit'], 2),
            ];
        }

        $debit = collect($ledger)->sum('debit');
        $credit = collect($ledger)->sum('credit');

        $data[] = [
            '1' => null,
            'name' => 'Total',
            'debit' => $debit > 0 ? number_format($debit, 2) : number_format(-$debit, 2),
            'credit' => $credit > 0 ? number_format($credit, 2) : number_format(-$credit, 2),

        ];

        $data[] = [
            '1' => null,
            'name' => 'Difference',
            '3' => null,
            '4' => number_format($debit-$credit,2),

        ];


        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne(['Type', 'Account Name', 'Dr', 'Cr']);

        $csv->insertAll($data);

        $csv->output('Trial & Balance ' . date('d M Y h:i A') . '.csv');

//        $request = request();
//        $from_date = $request['from_date'];
//        $to_date = $request['to_date'];
//
//        $export = new TrialBalanceExport($from_date, $to_date);
//
//        return Excel::download($export,'Trial & Balance'. date('d M Y h:i A') . '.xlsx');
    }

}