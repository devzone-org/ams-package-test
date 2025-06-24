<?php


namespace Devzone\Ams\Http\Livewire\Reports;


use Carbon\Carbon;
use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use mysql_xdevapi\Collection;

class ProfitLoss extends Component
{

    public $from_date;
    public $to_date;
    public $closing_vouchers = 'hide';
    public $heading = [];
    public $report = [];

    public function mount()
    {
        $this->from_date = Carbon::now()->startOfMonth()->subMonth(3)->format('d M Y');
        $this->to_date = date('d M Y');
        $this->search();
    }

    public function render()
    {
        return view('ams::livewire.reports.profit-loss');
    }

    private function formatDate($date)
    {
        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');
    }

    public function search()
    {

        $report = \Devzone\Ams\Models\Ledger::from('ledgers as l')
            ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
            ->when(!empty($this->closing_vouchers) && strtolower($this->closing_vouchers) == 'hide', function ($q) {
                return $q->leftJoin('day_closing as dc','dc.voucher_no','l.voucher_no')
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
                DB::raw("DATE_FORMAT(l.posting_date,'%Y-%m') as month"))
            ->where('l.posting_date', '>=', $this->formatDate($this->from_date))
            ->where('l.posting_date', '<=', $this->formatDate($this->to_date))
            ->where('l.is_approve', 't')
            ->whereIn('coa.type', ['Income', 'Expenses'])
            ->groupBy(DB::raw("DATE_FORMAT(l.posting_date,'%Y-%m')"))
            ->groupBy('l.account_id')
//            ->orderBy('coa.name', 'asc')
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
                'p_ref' => $p_ref
            ];
        }

        foreach ($pnl as $key => $d) {
            $same_account_total_balance = collect($pnl)->where('account_id', $d['account_id'])->sum('balance');
            $pnl[$key]['same_account_total_balance'] = $same_account_total_balance;
        }

        $this->report = $pnl;

    }
}
