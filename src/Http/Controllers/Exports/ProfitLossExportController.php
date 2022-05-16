<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Exports\ProfitLossExport;
use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use SplTempFileObject;
use Excel;

class ProfitLossExportController
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


    public function download()
    {

        $report = \Devzone\Ams\Models\Ledger::from('ledgers as l')
            ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
            ->select(
                DB::raw('sum(l.debit) as debit'),
                DB::raw('sum(l.credit) as credit'),
                'coa.name',
                'coa.type',
                'coa.nature',
                'coa.is_contra',
                'coa.reference',
                'coa.sub_account',
                'l.account_id',
                DB::raw("DATE_FORMAT(l.posting_date,'%Y-%m') as month"))
            ->where('l.posting_date', '>=', $this->formatDate($this->from_date))
            ->where('l.posting_date', '<=', $this->formatDate($this->to_date))
            ->where('l.is_approve', 't')
            ->whereIn('coa.type', ['Income', 'Expenses'])
            ->groupBy(DB::raw("DATE_FORMAT(l.posting_date,'%Y-%m')"))
            ->groupBy('l.account_id')
            ->orderBy('coa.name', 'asc')
            ->get();

        $account_ids = array_unique($report->pluck('sub_account')->toArray());
        $accounts = ChartOfAccount::whereIn('id', $account_ids)->get();
        $heading = $report->groupBy('month')->sortKeys()->keys()->toArray();

        $pnl = [];
        foreach ($report as $r) {

            if ($r->nature == 'd') {
                if ($r->is_contra == 'f') {
                    $balance = $r->debit - $r->credit;
                } else {
                    $balance = -($r->credit - $r->debit);
                }
            } else {
                if ($r->is_contra == 'f') {
                    $balance = $r->credit - $r->debit;
                } else {
                    $balance = -($r->debit - $r->credit);
                }
            }
            $p_ref = $accounts->firstWhere('id', $r->sub_account);
            if (!empty($p_ref)) {
                $p_ref = $p_ref->reference;
            } else {
                $p_ref = null;
            }
            $pnl[] = [
                'name' => $r->name,
                'type' => $r->type,
                'nature' => $r->nature,
                'is_contra' => $r->is_contra,
                'month' => $r->month,
                'balance' => $balance,
                'account_id' => $r->account_id,
                'reference' => $r->reference,
                'p_ref' => $p_ref
            ];
        }

        $report = $pnl;
        $data = [];
        $balance = '';
        foreach ($report as $r) {
//            foreach ($heading as $h)
//
//                $first = collect($report)->where('account_id', $key)->where('month', $h)->first();
//            if (!empty($first)) {
//
//                $balance = $first['balance'];
//            } else {
//
//                $balance = '-';
//
//            }

            $data[]=[
              'name' => $r['name'],
                'balance' => $balance

            ];

        }
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $months = [];
        foreach($heading as $h){
            $months[] = date('M Y', strtotime($h));
        }

        $csv->insertOne(['account_head',$months , 'total', 'debit', 'credit', 'balance',]);

        $csv->insertAll($data);

        $csv->output('ledger.csv');


//        $request = request();
//        $from_date = $request['from_date'];
//        $to_date = $request['to_date'];
//
//        $export = new ProfitLossExport( $from_date, $to_date);
//
//        return Excel::download($export,'P&L'. date('d M Y h:i A') . '.xlsx');
    }

}