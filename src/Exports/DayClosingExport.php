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


class DayClosingExport implements FromView, WithStyles, ShouldAutoSize, WithEvents
{
    protected $user_account_id;
    protected $from_date;
    protected $to_date;

    public function __construct($user_account_id, $from_date, $to_date)
    {

        $this->user_account_id = $user_account_id;
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
        $report = \Devzone\Ams\Models\DayClosing::from('day_closing as dc')
            ->join('users as u', 'u.account_id', '=', 'dc.account_id')
            ->join('users as t', 't.account_id', '=', 'dc.transfer_to')
            ->join('users as c', 'c.id', '=', 'dc.close_by')
            ->select('dc.*', 'u.name as user_id', 't.name as transfer_name', 'c.name as close_by')
            ->where('dc.account_id', $this->user_account_id)
            ->whereDate('dc.created_at', '>=', $this->formatDate($this->from_date))
            ->whereDate('dc.created_at', '<=', $this->formatDate($this->to_date))
            ->orderBy('dc.date', 'desc')
            ->get()
            ->toArray();

        return view('ams::exports.reports.day-closing-report', [
            'report' => $report,
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
