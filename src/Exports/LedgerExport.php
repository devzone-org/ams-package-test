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
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class LedgerExport implements FromView, WithStyles, ShouldAutoSize, WithEvents,WithColumnWidths
{
    protected $account_id;
    protected $from_date;
    protected $to_date;

    public function __construct($account_id, $from_date, $to_date)
    {

        $this->account_id = $account_id;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }
    public function columnWidths(): array
    {
        return [
            'C' => 55
        ];
    }

    public function view(): View
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


        return view('ams::exports.reports.ledger', [
            'ledger' => $ledger,
            'account_name_s' => $account_name,
            'from' => $this->from_date,
            'to' => $this->to_date,
            'opening_balance' => $opening_balance,
            'account_details' => $account
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