<?php

namespace Devzone\Ams\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrialBalanceExport implements FromView, WithStyles, ShouldAutoSize, WithEvents
{

    protected $from_date;
    protected $to_date;

    public function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function view(): View
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


        return view('ams::exports.reports.trial-balance', [
            'ledger' => $ledger,
            'from' => $this->from_date,
            'to' => $this->to_date,

        ]);
    }


    private function formatDate($date)
    {

        return \Carbon\Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');

    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(2)->setRowHeight(20);


        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 18]],
            2 => ['font' => ['bold' => true, 'size' => 13]],
            3 => ['font' => ['bold' => true, 'size' => 12]],



        ];
    }

    public function registerEvents(): array
    {

        return [

            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:G1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A2:G2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A3:G3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);


                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize('13');

            },
        ];
    }
}
