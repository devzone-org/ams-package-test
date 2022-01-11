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


class CoaExport implements FromView, WithStyles, ShouldAutoSize, WithEvents
{
  protected $type;

    public function __construct($type)
    {

        $this->type = $type;

    }
//    public function columnWidths(): array
//    {
//        return [
//            'C' => 55
//        ];
//    }

    public function view(): View
    {
        $coa = ChartOfAccount::from('chart_of_accounts as coa')
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


        return view('ams::exports.reports.coa', [
            'coa' => $coa,

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

//                $event->sheet->getDelegate()->getStyle('A3:G3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
//                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);


                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize('13');

            },
        ];
    }


}
