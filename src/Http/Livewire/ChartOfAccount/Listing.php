<?php

namespace Devzone\Ams\Http\Livewire\ChartOfAccount;

use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Listing extends Component
{
    public $type;
    public $confirm;
    public $primary_id;

    public function render()
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

        return view('ams::livewire.chart-of-accounts.listing', compact('coa'));
    }

    public function changeStatusConfirm(){
        if (auth()->user()->can('2.edit.coa.status')) {
            $account = ChartOfAccount::find($this->primary_id);
            if ($account->status == 't') {
                $account->update([
                    'status' => 'f'
                ]);
            } else {
                $account->update([
                    'status' => 't'
                ]);
            }
        }
        $this->confirm = false;
    }
    public function changeStatus($id)
    {
        $this->primary_id  = $id;
        $this->confirm = true;


    }
}
