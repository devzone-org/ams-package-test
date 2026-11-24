<?php


namespace Devzone\Ams\Http\Livewire\Reports;


use Carbon\Carbon;
use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\User;
use function Couchbase\defaultDecoder;

class ProfitLossDateWise extends Component
{

    public $from_date;
    public $to_date;
    public $closing_vouchers = 'hide';
    public $type = 'posting_date';
    public $from_time = '00:00';
    public $to_time = '23:59';
    public $heading = [];
    public $report = [];
    public $year;
    public $selected_user ;
    public $users = [];

    public function mount()
    {
        $this->from_date = Carbon::now()->startOfMonth()->format('d M Y');
        $this->to_date = date('d M Y');
        $this->search();
    }

    public function render()
    {
        return view('ams::livewire.reports.profit-loss-date-wise');
    }

    private function formatDate($date)
    {
        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');
    }

    // returns [column, [from, to]] for the selected type
    private function dateRange()
    {
        if ($this->type == 'approved_at') {
            return ['l.approved_at', [
                Carbon::createFromFormat('d M Y H:i', $this->from_date . ' ' . ($this->from_time ?: '00:00'))->format('Y-m-d H:i:00'),
                Carbon::createFromFormat('d M Y H:i', $this->to_date . ' ' . ($this->to_time ?: '23:59'))->format('Y-m-d H:i:59'),
            ]];
        }
        return ['l.posting_date', [$this->formatDate($this->from_date), $this->formatDate($this->to_date)]];
    }

    public function search()
    {
        [$col, $range] = $this->dateRange();
        $from = Carbon::parse($this->from_date);
        $to = Carbon::parse($this->to_date);


        if($from->month != $to->month){
            $this->addError('from_date', 'From Date and To Date must be in the same month.');
            return;
        }


        $report = \Devzone\Ams\Models\Ledger::from('ledgers as l')
            ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
            ->when(!empty($this->closing_vouchers) && strtolower($this->closing_vouchers) == 'hide', function ($q) {
                return $q->leftJoin('closing_summary_accounts as dc','dc.voucher_no','l.voucher_no')
                    ->whereNull('dc.voucher_no');
            })
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
                DB::raw("DATE_FORMAT({$col},'%d %M') as month"),
                DB::raw("DATE_FORMAT({$col},'%Y-%m-%d') as date"))
            ->whereBetween($col, $range)
            ->where('l.is_approve', 't')
            ->whereIn('coa.type', ['Income', 'Expenses'])
            ->groupBy(DB::raw("DATE_FORMAT({$col},'%d %M')"))
            ->groupBy('l.account_id')
            ->orderBy('coa.name', 'asc')
            ->get();

        $account_ids = array_unique($report->pluck('sub_account')->toArray());
        $accounts = ChartOfAccount::whereIn('id', $account_ids)->get();
        $this->heading = $report->groupBy('month')->sortKeys()->keys()->toArray();

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

        $this->report = $pnl;


    }
}
