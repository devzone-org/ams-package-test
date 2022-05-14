<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use SplTempFileObject;
use Excel;

class LedgerExportController
{
    protected $account_id;
    protected $from_date;
    protected $to_date;

    public function __construct()
    {
        $request = request();
        $this->account_id = $request['id'];
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

        $account_name = '';
        $account = ChartOfAccount::where('id', $this->account_id)->get();
        if ($account->isNotEmpty()) {
            $account = $account->first()->toArray();
            $account_name = $account['name'];
        }

        $ledger = [];
        $opening_balance = 0;
        if (!empty($this->account_id) && !empty($this->from_date) && !empty($this->to_date)) {
            $ledger = \Devzone\Ams\Models\Ledger::where('is_approve', 't')
                ->where('posting_date', '>=', $this->formatDate($this->from_date))
                ->where('posting_date', '<=', $this->formatDate($this->to_date))
                ->where('account_id', $this->account_id)
                ->select('voucher_no', 'reference', 'posting_date', 'description', 'debit', 'credit', 'account_id')
                ->orderBy('posting_date')->orderBy('voucher_no')
                ->get()->toArray();
            $opening = \Devzone\Ams\Models\Ledger::where('is_approve', 't')
                ->where('posting_date', '<', $this->formatDate($this->from_date))
                ->where('account_id', $this->account_id)
                ->select(DB::raw('sum(debit) as debit'), DB::raw('sum(credit) as credit'))
                ->first();
            if ($account['nature'] == 'd') {
                if ($account['is_contra'] == 'f') {
                    $opening_balance = $opening['debit'] - $opening['credit'];
                } else {
                    $opening_balance = $opening['credit'] - $opening['debit'];
                }

            } else {
                if ($account['is_contra'] == 'f') {
                    $opening_balance = $opening['credit'] - $opening['debit'];
                } else {
                    $opening_balance = $opening['debit'] - $opening['credit'];
                }
            }
        }

        $data = [];
        $balance = $opening_balance;

        foreach ($ledger as $key=>  $en){

            if ($account['nature'] == 'd') {
                if($account['is_contra']=='f'){
                    $balance = $balance+ $en['debit'] - $en['credit'];
                } else {
                    $balance = $balance- $en['debit'] + $en['credit'];
                }

            } else {
                if($account['is_contra']=='f') {
                    $balance = $balance- $en['debit'] + $en['credit'];
                } else {
                    $balance = $balance+ $en['debit'] - $en['credit'];
                }
            }

            $data[]=[
                'voucher_no' => $en['voucher_no'],
                'posting_date' => $en['posting_date'],
                'description' => !empty($en['reference']) ?? '' . $en['description'],
                'debit' => $en['debit'],
                'credit' => $en['credit'],
                'balance' => $balance,
            ];
        }

        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne(['voucher_no', 'posting_date', 'description', 'debit', 'credit', 'balance']);

        $csv->insertAll($data);

        $csv->output('ledger.csv');

//        $request = request();
//        $account_id = $request['id'];
//        $coa = ChartOfAccount::find($request['id']);
//        $from_date = $request['from_date'];
//        $to_date = $request['to_date'];
//
//        $export = new LedgerExport($account_id, $from_date, $to_date);
//
//        return Excel::download($export, 'GL ' . $coa['name'] . ' - ' . date('d M Y h:i A') . '.xlsx');
    }
}