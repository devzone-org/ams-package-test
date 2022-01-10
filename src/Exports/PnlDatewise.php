<?php

namespace Devzone\Ams\Exports;

use App\Models\Rate\Currency;
use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class PnlDatewise implements FromView, WithStyles, ShouldAutoSize, WithEvents
{
    protected $from_date;
    protected $to_date;

    public function __construct( $from_date, $to_date)
    {

        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }
//    public function columnWidths(): array
//    {
//        return [
//            'C' => 55
//        ];
//    }

    public function view(): View
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
                DB::raw("DATE_FORMAT(l.posting_date,'%d %M') as month"),
                DB::raw("DATE_FORMAT(l.posting_date,'%Y-%m-%d') as date"))
            ->where('l.posting_date', '>=', $this->formatDate($this->from_date))
            ->where('l.posting_date', '<=', $this->formatDate($this->to_date))
            ->where('l.is_approve', 't')
            ->whereIn('coa.type', ['Income', 'Expenses'])
            ->groupBy(DB::raw("DATE_FORMAT(l.posting_date,'%d %M')"))
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
                'p_ref' => $p_ref,
                'date' => $r->date
            ];
        }
        $report = $pnl;


        return view('ams::exports.reports.pnl-datewise', [
            'report' => $report,
            'heading' => $heading,
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
        $sheet->getRowDimension(1)->setRowHeight(35);
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