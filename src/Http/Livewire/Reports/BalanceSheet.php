<?php


namespace Devzone\Ams\Http\Livewire\Reports;


use Carbon\Carbon;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\EquityRatio;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BalanceSheet extends Component
{
    public $level3 = [];
    public $level4 = [];
    public $level5 = [];
    public $data;
    public $pnl;
    public $asat;


    public function mount()
    {

        $this->asat = date('d M Y');
        $this->search();

    }

    private function formatDate($date)
    {
        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');
    }

    public function render()
    {
        return view('ams::livewire.reports.balance-sheet');
    }


    public function resetSearch()
    {
        $this->reset('asat');
    }


    public function search()
    {

        $this->reset('level3', 'level4', 'level5', 'data', 'pnl');
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
                'coa.level',
                'coa.id'
            )
            ->where('l.is_approve', 't')
            ->where('l.posting_date', '<=', $this->formatDate($this->asat))
            ->whereIn('coa.type', ['Assets', 'Liabilities', 'Equity'])
            ->groupBy('l.account_id')
            ->orderBy('coa.name', 'asc')
            ->get();

        $this->calculateProfit();

        $l4 = array_unique($report->pluck('sub_account')->toArray());
        $l4_accounts = ChartOfAccount::whereIn('id', $l4)->get();


        $l3 = array_unique($l4_accounts->pluck('sub_account')->toArray());
        $l3_accounts = ChartOfAccount::whereIn('id', $l3)->get();
        $this->data = [];

        foreach ($l3_accounts as $l3) {
            $balance_v3 = 0;
            $this->data['l3' . $l3['id']] = true;
            foreach ($l4_accounts->where('sub_account', $l3->id) as $l4) {
                $balance_v4 = 0;
                $this->data['l4' . $l4->id] = false;
                foreach ($report->where('sub_account', $l4->id) as $r) {
                    $balance = 0;
                    if ($r->type == 'Equity' && $r->is_contra == 't') {
                        continue;
                    }

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
                    if ($r->type == 'Equity') {

                        $acc = EquityRatio::where('account_id', $r->id)->first();
                        if(!empty($acc)){
                            $draw = $report->firstWhere('id', optional($acc)->drawing_account_id);
                            $drawings = 0;
                            if (!empty($draw)) {
                                $drawings = $draw['debit'] - $draw['credit'];
                            }
                            $balance = $balance + ($this->pnl * optional($acc)->ratio) - $drawings;
                        }

                    }
                    $balance_v4 += $balance;
//                  Converting -0 to 0 if the amount is effectively zero
                    if ($balance == 0) {
                        $balance = 0;
                    }

                    $this->level5[] = [
                        'name' => $r->name,
                        'type' => $r->type,
                        'nature' => $r->nature,
                        'is_contra' => $r->is_contra,
                        'level' => $r->level,
                        'balance' => $balance,
                        'id' => $r->id,
                        'sub_account' => $r->sub_account,
                    ];
                }
                $this->level4[] = [
                    'name' => $l4->name,
                    'type' => $l4->type,
                    'nature' => $l4->nature,
                    'is_contra' => $l4->is_contra,
                    'level' => $l4->level,
                    'balance' => $balance_v4,
                    'id' => $l4->id,
                    'sub_account' => $l4->sub_account,
                ];
                $balance_v3 += $balance_v4;
            }
            $this->level3[] = [
                'name' => $l3->name,
                'type' => $l3->type,
                'nature' => $l3->nature,
                'is_contra' => $l3->is_contra,
                'level' => $l3->level,
                'balance' => $balance_v3,
                'id' => $l3->id,
                'sub_account' => $l3->sub_account,
            ];
        }


        $this->data = json_encode($this->data);
    }

    private function calculateProfit()
    {
        $pnl = \Devzone\Ams\Models\Ledger::from('ledgers as l')
            ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
            ->select(
                DB::raw('sum(l.debit) as debit'),
                DB::raw('sum(l.credit) as credit'),
                'coa.type'
            )
            ->where('l.is_approve', 't')
            ->where('l.posting_date', '<=', $this->formatDate($this->asat))
            ->whereIn('coa.type', ['Income', 'Expenses'])
            ->groupBy('coa.type')
            ->get();
        $total_expense = 0;
        $total_income = 0;
        $expense = $pnl->where('type', 'Expenses')->first();
        if (!empty($expense)) {
            $total_expense = $expense['debit'] - $expense['credit'];
        }
        $income = $pnl->where('type', 'Income')->first();
        if (!empty($income)) {
            $total_income = $income['credit'] - $income['debit'];
        }


        $this->pnl = ($total_income - $total_expense);

    }
}
