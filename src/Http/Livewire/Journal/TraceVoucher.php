<?php

namespace Devzone\Ams\Http\Livewire\Journal;

use Carbon\Carbon;
use Devzone\Ams\Models\Ledger;
use Livewire\Component;

class TraceVoucher extends Component
{
    public $temp_list;
    public $range;
    public $date_range;
    public $from;
    public $voucher_from;
    public $voucher_to;
    public $to;
    public $voucher_no;
    public $type;
    public $last_voucher;

    public function mount()
    {
        $this->from = date('d M Y', strtotime('-7 days'));
        $this->to = date('d M Y');
        $this->range = 'seven_days';
        $this->type = 'voucher';
        $this->last_voucher = Ledger::max('voucher_no');
        // $this->search();
    }

    private function formatDate($date)
    {
        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');
    }

    public function search()
    {
        $this->temp_list = Ledger::from('ledgers as l')
            ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
            ->join('users as u', 'u.id', '=', 'l.posted_by')
            ->where('l.is_approve', 't')
            ->when(!empty($this->voucher_to), function ($q) {
                return $q->where('l.voucher_no', '<=', $this->voucher_to);
            })
            ->when(!empty($this->voucher_from), function ($q) {
                return $q->where('l.voucher_no', '>=', $this->voucher_from);
            })
            ->when(empty($this->voucher_no) && empty($this->voucher_to) && empty($this->voucher_from) && !empty($this->to), function ($q) {
                return $q->whereDate('l.posting_date', '<=', $this->formatDate($this->to));
            })
            ->when(empty($this->voucher_no) && empty($this->voucher_to) && empty($this->voucher_from) && !empty($this->from), function ($q) {
                return $q->whereDate('l.posting_date', '>=', $this->formatDate($this->from));
            })
            ->when(!empty($this->voucher_no), function ($q) {
                return $q->where('l.voucher_no', $this->voucher_no);
            })
            ->select(
                'l.*',
                'coa.name',
                'coa.code',
                'u.name as posting'
            )
            ->orderBy('l.voucher_no')
            ->orderBy('l.posting_date')
            ->get();
    }

    public function print($voucher_no, $print = null)
    {
        if (!empty($print) && !empty($voucher_no)) {
            $this->dispatchBrowserEvent('print-voucher', ['voucher_no' => $voucher_no, 'print' => 'true']);
        }
    }

    public function resetSearch()
    {
        $this->reset('temp_list', 'voucher_no', 'type', 'voucher_from', 'voucher_to');

        $this->type = 'voucher';
        $this->range = 'seven_days';
        $this->date_range = false;
        $this->from = date('d M Y', strtotime('-7 days'));
        $this->to = date('d M Y');
        $this->dispatchBrowserEvent('resetPikaday');
    }

    public function updatedRange($val)
    {
        if ($val == 'custom_range') {
            $this->date_range = true;
        } elseif ($val == 'seven_days') {
            $this->date_range = false;
            $this->from = date('d M Y', strtotime('-7 days'));
            $this->to = date('d M Y');
            $this->dispatchBrowserEvent('resetPikaday');
            // $this->search();
        } elseif ($val == 'thirty_days') {
            $this->date_range = false;
            $this->from = date('d M Y', strtotime('-30 days'));
            $this->to = date('d M Y');
            $this->dispatchBrowserEvent('resetPikaday');
            // $this->search();
        } elseif ($val == 'yesterday') {
            $this->date_range = false;
            $this->from = date('d M Y', strtotime('-1 days'));
            $this->to = date('d M Y', strtotime('-1 days'));
            $this->dispatchBrowserEvent('resetPikaday');
            // $this->search();
        } elseif ($val == 'today') {
            $this->date_range = false;
            $this->from = date('d M Y');
            $this->to = date('d M Y');
            $this->dispatchBrowserEvent('resetPikaday');
            // $this->search();
        }
    }

    public function updatedType($key)
    {
        if ($key != 'date_range') {
            $this->date_range = false;
            $this->range = 'seven_days';
            $this->from = date('d M Y', strtotime('-7 days'));
            $this->to = date('d M Y');
            $this->dispatchBrowserEvent('resetPikaday');
        }

        if ($key != 'voucher_range') {
            $this->voucher_from = null;
            $this->voucher_to = null;
        }

        if ($key != 'voucher') {
            $this->voucher_no = null;
        }
    }

    public function render()
    {
        return view('ams::livewire.journal.trace-voucher');
    }
}
