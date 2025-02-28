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
        $bal = [];

        $data[] = [
            'name' => 'Revenue',
            'balance' => null,
            'total' => null,
        ];

        foreach (collect($report)->where('type', 'Income')->groupBy('account_id') as $key => $en) {


            $bal = [];
            foreach ($heading as $h) {
                $first = collect($report)->where('account_id', $key)->where('month', $h)->first();
                if (auth()->user()->cannot('2.hide-income')  || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) {
                    if (!empty($first)) {
                        $bal[] = $first['balance'];
                    } else {
                        $bal[] = '-';
                    }
                }


            }
            $data[] = [
                'name' => $en->first()['name'],
                'balance' => $bal,
                'total' => (auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) ? number_format(collect($report)->where('account_id', $key)->sum('balance'), 2) : '',
            ];

        }

        $bal = [];
        foreach ($heading as $h) {
            if (auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) {

                $bal[] = collect($report)->where('type', 'Income')->where('month', $h)->sum('balance');
            }
        }

        $data [] = [
            'name' => 'Total Revenue',
            'balance' => $bal,
            'total' => (auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) ? number_format(collect($report)->where('type', 'Income')->sum('balance'), 2) : '',
        ];

        $data[] = [
            'name' => null,
            'balance' => null,
            'total' => null
        ];

        $data[] = [
            'name' => 'Less Cost of Sales',
            'balance' => null,
            'total' => null,
        ];

        foreach (collect($report)->where('p_ref', 'cost-of-sales-4')->groupBy('account_id') as $key => $en) {
            $bal = [];
            foreach ($heading as $h) {
                $first = collect($report)->where('account_id', $key)->where('month', $h)->first();
                if (auth()->user()->cannot('2.hide-expenses')  || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) {
                    if (!empty($first)) {
                        $bal[] = $first['balance'];
                    } else {
                        $bal[] = '-';
                    }
                }
            }

            $data[] = [
                'name' => $en->first()['name'],
                'balance' => $bal,
                'total' => (auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) ? number_format(collect($report)->where('account_id', $key)->sum('balance'), 2) : '',
            ];
        }

        $bal = [];
        foreach ($heading as $h) {
            if(auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) {
                $bal[] = collect($report)->where('p_ref', 'cost-of-sales-4')->where('month', $h)->sum('balance');
            }
        }
        $data [] = [
            'name' => 'Total Expenses',
            'balance' => $bal,
            'total' => (auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) ? number_format(collect($report)->where('p_ref', 'cost-of-sales-4')->sum('balance'), 2): '',
        ];

        $data[] = [
            'name' => null,
            'balance' => null,
            'total' => null
        ];

        $bal = [];
        foreach ($heading as $h) {
            if (auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) {
                $bal[] = collect($report)->where('type', 'Income')->where('month', $h)->sum('balance') - collect($report)->where('p_ref', 'cost-of-sales-4')->where('month', $h)->sum('balance');
            }
        }
        $data [] = [
            'name' => 'Gross Profit',
            'balance' => $bal,
            'total' => (auth()->user()->cannot('2.hide-income') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) ? number_format(collect($report)->where('type', 'Income')->sum('balance') - collect($report)->where('p_ref', 'cost-of-sales-4')->sum('balance'), 2) : '',
        ];

        $data[] = [
            'name' => null,
            'balance' => null,
            'total' => null
        ];

        $data[] = [
            'name' => 'Other Expenses',
            'balance' => null,
            'total' => null,];

        foreach (collect($report)->where('type', 'Expenses')->where('p_ref', '!=', 'cost-of-sales-4')->groupBy('account_id') as $key => $en) {
            $bal = [];
            foreach ($heading as $h) {
                $first = collect($report)->where('account_id', $key)->where('month', $h)->first();
                if (auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) {
                    if (!empty($first)) {
                        $bal[] = $first['balance'];
                    } else {
                        $bal[] = '-';
                    }
                }
            }

            $data[] = [
                'name' => $en->first()['name'],
                'balance' => $bal,
                'total' => (auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) ? number_format(collect($report)->where('account_id', $key)->sum('balance'), 2) : '',
            ];

        }

        $bal = [];
        foreach ($heading as $h) {
            if (auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) {
                $bal[] = collect($report)->where('type', 'Expenses')->where('p_ref', '!=', 'cost-of-sales-4')->where('month', $h)->sum('balance');
            }
        }
        $data [] = [
            'name' => 'Total Other Expenses',
            'balance' => $bal,
            'total' => (auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) ? number_format(collect($report)->where('type', 'Expenses')->where('p_ref', '!=', 'cost-of-sales-4')->sum('balance'), 2) : '',
        ];

        $data[] = [
            'name' => null,
            'balance' => null,
            'total' => null
        ];
        $bal = [];
        foreach ($heading as $h) {
            if(auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) {
                $bal[] = collect($report)->where('type', 'Income')->where('month', $h)->sum('balance') - collect($report)->where('type', 'Expenses')->where('month', $h)->sum('balance');
            }
        }
        $data [] = [
            'name' => 'Net Profit/(Loss)',
            'balance' => $bal,
            'total' => (auth()->user()->cannot('2.hide-expenses') || env('SKIP_ACCOUNTANT_RESTRICTION', false) === true) ? number_format(collect($report)->where('type', 'Income')->sum('balance') - collect($report)->where('type', 'Expenses')->sum('balance'), 2) : '',
        ];


        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $months = [];
        foreach ($heading as $h) {
            $months[] = date('M Y', strtotime($h));
        }

        array_unshift($months, 'Account Head');
        array_push($months, 'Total');
        $csv->insertOne($months);


        foreach ($data as $k => $val) {
            if (empty($val['balance'])) {
                $csv->insertAll([[$val['name']]]);
            } else {
                $rec = [$val['name']];
                $rec = array_merge($rec, $val['balance']);
                array_push($rec, $val['total']);
                $csv->insertAll([$rec]);

            }
        }

        $csv->output('P&L ' . date('d M Y h:i A') . '.csv');


//        $request = request();
//        $from_date = $request['from_date'];
//        $to_date = $request['to_date'];
//
//        $export = new ProfitLossExport( $from_date, $to_date);
//
//        return Excel::download($export,'P&L'. date('d M Y h:i A') . '.xlsx');
    }

}