<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Models\ChartOfAccount;
use League\Csv\Writer;
use SplTempFileObject;
use Illuminate\Support\Facades\DB;


class BalanceSheetExport
{
    protected $asat;

    public function __construct()
    {
        $request = request();
        $this->asat = $request['asat'];
    }

    private function formatDate($date)
    {
        return \Carbon\Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');
    }

    public function download()
    {
        $level3 = [];
        $level4 = [];
        $level5 = [];

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
                'coa.level',
                'coa.id'
            )
            ->where('l.is_approve', 't')
            ->where('l.posting_date', '<=', $this->formatDate($this->asat))
            ->whereIn('coa.type', ['Assets', 'Liabilities', 'Equity'])
            ->groupBy('l.account_id')
            ->orderBy('coa.name', 'asc')
            ->get();

        $this->calculateProfit($report);

        $l4 = array_unique($report->pluck('sub_account')->toArray());
        $l4_accounts = ChartOfAccount::whereIn('id', $l4)->get();


        $l3 = array_unique($l4_accounts->pluck('sub_account')->toArray());
        $l3_accounts = ChartOfAccount::whereIn('id', $l3)->get();
        $this->data = [];

        foreach ($l3_accounts as $l3) {
            $balance_v3 = 0;
//            $this->data['l3' . $l3['id']] = true;
            foreach ($l4_accounts->where('sub_account', $l3->id) as $l4) {
                $balance_v4 = 0;
//                $this->data['l4' . $l4->id] = false;
                foreach ($report->where('sub_account', $l4->id) as $r) {
                    $balance = 0;
                    if ($r->type == 'Equity' && $r->is_contra == 't') {
                        continue;
                    }

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
                    if ($r->type == 'Equity') {
                        $balance = $balance + $this->pnl;
                    }
                    $balance_v4 += $balance;
                    $level5[] = [
                        'name' => $r->name,
                        'type' => $r->type,
                        'nature' => $r->nature,
                        'is_contra' => $r->is_contra,
                        'level' => $r->level,
                        'balance' => $balance,
                        'id' => $r->id,
                        'sub_account' => $r->sub_account,
                    ];
                }
                $level4[] = [
                    'name' => $l4->name,
                    'type' => $l4->type,
                    'nature' => $l4->nature,
                    'is_contra' => $l4->is_contra,
                    'level' => $l4->level,
                    'balance' => $balance_v4,
                    'id' => $l4->id,
                    'sub_account' => $l4->sub_account,
                ];
                $balance_v3 += $balance_v4;
            }
            $level3[] = [
                'name' => $l3->name,
                'type' => $l3->type,
                'nature' => $l3->nature,
                'is_contra' => $l3->is_contra,
                'level' => $l3->level,
                'balance' => $balance_v3,
                'id' => $l3->id,
                'sub_account' => $l3->sub_account,
            ];
        }
        $data = [];
        foreach (collect($level3)->groupBy('type')->toArray() as $type => $lvl3) {
            $data[] = [
                'name' => $type,
                '2' => null,
                '3' => null,
                '4' => null,
                '5' => null,
                '6' => null,
                '7' => null

            ];
            foreach ($lvl3 as $l3) {
                $l3_balance = '';

                if ($type == 'Assets') {
                    if (auth()->user()->cannot('2.hide-assets')) {
                        $l3_balance = number_format($l3['balance'], 2);
                    }
                } elseif ($type == 'Liabilities') {
                    if (auth()->user()->cannot('2.hide-liabilities')) {
                        $l3_balance = number_format($l3['balance'], 2);
                    }
                } elseif ($type == 'Equity') {
                    if (auth()->user()->cannot('2.hide-equity')) {
                        $l3_balance = number_format($l3['balance'], 2);
                    }
                } elseif ($type == 'Income') {
                    if (auth()->user()->cannot('2.hide-income')) {
                        $l3_balance = number_format($l3['balance'], 2);
                    }
                } elseif ($type == 'Expenses') {
                    if (auth()->user()->cannot('2.hide-expenses')) {
                        $l3_balance = number_format($l3['balance'], 2);
                    }
                } else {
                    $l3_balance = number_format($l3['balance'], 2);
                }

                $data[] = [
                    '1' => null,
                    'name' => $l3['name'],
                    '3' => null,
                    '4' => null,
                    '5' => null,
                    '6' => null,
                    'balance' => $l3_balance,


                ];


                foreach (collect($level4)->where('sub_account', $l3['id']) as $l4) {

                    $l4_balance = '';

                    if ($type == 'Assets') {
                        if (auth()->user()->cannot('2.hide-assets')) {
                            $l4_balance = number_format($l4['balance'], 2);
                        }
                    } elseif ($type == 'Liabilities') {
                        if (auth()->user()->cannot('2.hide-liabilities')) {
                            $l4_balance = number_format($l4['balance'], 2);
                        }
                    } elseif ($type == 'Equity') {
                        if (auth()->user()->cannot('2.hide-equity')) {
                            $l4_balance = number_format($l4['balance'], 2);
                        }
                    } elseif ($type == 'Income') {
                        if (auth()->user()->cannot('2.hide-income')) {
                            $l4_balance = number_format($l4['balance'], 2);
                        }
                    } elseif ($type == 'Expenses') {
                        if (auth()->user()->cannot('2.hide-expenses')) {
                            $l4_balance = number_format($l4['balance'], 2);
                        }
                    } else {
                        $l4_balance = number_format($l4['balance'], 2);
                    }
                    $data[] = [
                        '1' => null,
                        '2' => null,
                        'name' => $l4['name'],
                        '4' => null,
                        '5' => null,
                        '6' => null,
                        'balance' => $l4_balance,

                    ];
                    foreach (collect($level5)->where('sub_account', $l4['id']) as $l5) {
                        $l5_balance = '';

                        if ($type == 'Assets') {
                            if (auth()->user()->cannot('2.hide-assets')) {
                                $l5_balance = number_format($l5['balance'], 2);
                            }
                        } elseif ($type == 'Liabilities') {
                            if (auth()->user()->cannot('2.hide-liabilities')) {
                                $l5_balance = number_format($l5['balance'], 2);
                            }
                        } elseif ($type == 'Equity') {
                            if (auth()->user()->cannot('2.hide-equity')) {
                                $l5_balance = number_format($l5['balance'], 2);
                            }
                        } elseif ($type == 'Income') {
                            if (auth()->user()->cannot('2.hide-income')) {
                                $l5_balance = number_format($l5['balance'], 2);
                            }
                        } elseif ($type == 'Expenses') {
                            if (auth()->user()->cannot('2.hide-expenses')) {
                                $l5_balance = number_format($l5['balance'], 2);
                            }
                        } else {
                            $l5_balance = number_format($l5['balance'], 2);
                        }
                        $data[] = [
                            '1' => null,
                            '2' => null,
                            '3' => null,
                            'name' => $l5['name'],
                            '5' => null,
                            'balance' => $l5_balance,
                            '7' => null,
                        ];
                    }
                }
            }
            $type_total = '';

            if ($type == 'Assets') {
                if (auth()->user()->cannot('2.hide-assets')) {
                    $type_total = number_format(collect($level3)->where('type', $type)->sum('balance'));
                }
            } elseif ($type == 'Liabilities') {
                if (auth()->user()->cannot('2.hide-liabilities')) {
                    $type_total = number_format(collect($level3)->where('type', $type)->sum('balance'));
                }
            } elseif ($type == 'Equity') {
                if (auth()->user()->cannot('2.hide-equity')) {
                    $type_total = number_format(collect($level3)->where('type', $type)->sum('balance'));
                }
            } elseif ($type == 'Income') {
                if (auth()->user()->cannot('2.hide-income')) {
                    $type_total = number_format(collect($level3)->where('type', $type)->sum('balance'));
                }
            } elseif ($type == 'Expenses') {
                if (auth()->user()->cannot('2.hide-expenses')) {
                    $type_total = number_format(collect($level3)->where('type', $type)->sum('balance'));
                }
            } else {
                $type_total = number_format(collect($level3)->where('type', $type)->sum('balance'));
            }

            $data[] = [
                'name' => 'Total ' . $type,
                '2' => null,
                '3' => null,
                '4' => null,
                '5' => null,
                '6' => null,
                'balance' => $type_total,


            ];

            $data[] = [
                'name' => null,
                '2' => null,
                '3' => null,
                '4' => null,
                '5' => null,
                'balance' => null,
                '7' => null,

            ];
        }

        $liabilities = collect($level3)->where('type', 'Liabilities')->sum('balance');
        $equity = collect($level3)->where('type', 'Equity')->sum('balance');
        $total = $liabilities + $equity;
        $equity_and_liabilities = '';

        if(auth()->user()->cannot('2.hide-liabilities')){
            if(auth()->user()->cannot('2.hide-equity'))
            {
                $equity_and_liabilities = number_format($total, 2);
            }
        }
        $data[] = [
            'name' => ' Total Liabilities & Equity ',
            '2' => null,
            '3' => null,
            '4' => null,
            '5' => null,
            '6' => null,
            'balance' => $equity_and_liabilities,


        ];



        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne(['Name', '', '', '', '', env('CURRENCY','PKR'), env('CURRENCY','PKR')]);

        $csv->insertAll($data);

        $csv->output('Statement of Financial Position' . date('d M Y h:i A') . '.csv');
    }

    private function calculateProfit($report)
    {
        $pnl = \Devzone\Ams\Models\Ledger::from('ledgers as l')
            ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
            ->select(
                DB::raw('sum(l.debit) as debit'),
                DB::raw('sum(l.credit) as credit'),
                'coa.type'
            )
            ->where('l.is_approve', 't')
            ->where('l.posting_date', '<=', $this->formatDate($this->asat))
            ->whereIn('coa.type', ['Income', 'Expenses'])
            ->groupBy('coa.type')
            ->get();
        $total_expense = 0;
        $total_income = 0;
        $expense = $pnl->where('type', 'Expenses')->first();
        if (!empty($expense)) {
            $total_expense = $expense['debit'] - $expense['credit'];
        }
        $income = $pnl->where('type', 'Income')->first();
        if (!empty($income)) {
            $total_income = $income['credit'] - $income['debit'];
        }

        $total_partner = $report->where('type', 'Equity')->where('level', '5')->count();
        if (empty($total_partner)) {
            $total_partner = 1;
        }
        $this->pnl = ($total_income - $total_expense) / $total_partner;
    }

}