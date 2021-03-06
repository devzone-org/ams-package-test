<?php

namespace Devzone\Ams\Http\Controllers\Exports;

use Devzone\Ams\Exports\DayClosingExport;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use SplTempFileObject;
use Excel;

class DayClosingExportController
{

    protected $user_account_id;
    protected $from_date;
    protected $to_date;

    public function __construct()
    {
        $request = request();
        $this->user_account_id = $request['id'];
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

        $sth = \Devzone\Ams\Models\DayClosing::from('day_closing as dc')
            ->join('users as u', 'u.account_id', '=', 'dc.account_id')
            ->join('users as t', 't.account_id', '=', 'dc.transfer_to')
            ->join('users as c', 'c.id', '=', 'dc.close_by')
            ->select('dc.created_at','dc.voucher_no', 'u.name as user_id','c.name as close_by','dc.closing_balance','dc.physical_cash','dc.cash_retained', DB::raw('(dc.physical_cash - dc.closing_balance) as adjustment'), DB::raw('(dc.physical_cash - dc.cash_retained) as amount_transferred'),'t.name as transfer_name' )
            ->where('dc.account_id', $this->user_account_id)
            ->whereDate('dc.created_at', '>=', $this->formatDate($this->from_date))
            ->whereDate('dc.created_at', '<=', $this->formatDate($this->to_date))
            ->orderBy('dc.date', 'desc')
            ->get()->toArray();

        $data=[];
        foreach ($sth as $s){

            $data []=[
                'closing_date' => date('d M Y',strtotime($s['created_at'])),
                'voucher_no' => $s['voucher_no'],
                'user_id' => $s['user_id'],
                'close_by' => $s['close_by'],
                'close_at' => date('h:i A', strtotime($s['created_at'])),
                'closing_balance' => $s['closing_balance'],
                'physical_cash' => $s['physical_cash'],
                'cash_retained' => $s['cash_retained'],
                'adjustment' => $s['adjustment'],
                'amount_transferred' => $s['amount_transferred'],
                'transfer_to' => $s['transfer_name']

            ];

        }

        $csv = Writer::createFromFileObject(new SplTempFileObject());

        $csv->insertOne(['Closing Date', 'Voucher #', 'User ID', 'Closed By', 'Close At', 'System Cash', 'Physical Cash','Amount Retained' ,'Adjustment', 'Amount Transferred','Transfer To']);

        $csv->insertAll($data);

        $csv->output('Day Closing ' . date('d M Y h:i A') . '.csv');

//        $request = request();
//        $user_account_id = $request['id'];
//        $from_date = $request['from_date'];
//        $to_date = $request['to_date'];
//
//        $export = new DayClosingExport($user_account_id, $from_date, $to_date);
//
//        return Excel::download($export,  'Day CLosing' . date('d M Y h:i A') . '.xlsx');
    }
}