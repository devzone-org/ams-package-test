<?php


namespace Devzone\Ams\Http\Livewire\Reports;


use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Trial extends Component
{

    public $from_date;
    public $to_date;

    public function mount()
    {
        $this->from_date = date('Y-m-d', strtotime('-1 month'));
        $this->to_date = date('Y-m-d');
    }

    public function render()
    {
        $ledger = [];
        if (!empty($this->from_date) && !empty($this->to_date)) {
            $pnl = \Devzone\Ams\Models\Ledger::from('ledgers as l')
                ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
                ->where('l.is_approve', 't')
                ->where('l.posting_date','>=',$this->from_date)
                ->where('l.posting_date','<=',$this->to_date)
                ->whereIn('coa.type', ['Expenses', 'Income'])
                ->select(DB::raw('sum(debit) as debit'), DB::raw('sum(credit) as credit'), 'coa.type', 'coa.name','coa.code', 'coa.nature', 'coa.is_contra')
                ->groupBy('l.account_id')
                ->get();

            $balance = \Devzone\Ams\Models\Ledger::from('ledgers as l')
                ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
                ->where('l.is_approve', 't')
                ->where('l.posting_date','<=',$this->to_date)
                ->whereIn('coa.type', ['Assets', 'Liabilities','Equity'])
                ->select(DB::raw('sum(debit) as debit'), DB::raw('sum(credit) as credit'), 'coa.type', 'coa.name','coa.code', 'coa.nature', 'coa.is_contra')
                ->groupBy('l.account_id')
                ->get();


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
        }


        return view('ams::livewire.reports.trial', compact('ledger'));
    }
}
