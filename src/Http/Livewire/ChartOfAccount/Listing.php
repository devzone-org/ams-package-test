<?php

namespace Devzone\Ams\Http\Livewire\ChartOfAccount;

use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Listing extends Component
{
    public $type;
    public function render()
    {
        $coa = ChartOfAccount::from('chart_of_accounts as coa')
            ->leftJoin('ledgers as l','l.account_id','=','coa.id')
            ->when(!empty($this->type),function($q){
            return $q->where('coa.type',$this->type);
        })->select('coa.*',DB::raw('SUM(l.debit) as debit'),DB::raw('SUM(l.credit) as credit'),
                DB::raw('max(l.posting_date) as posting_date'))->groupBy('coa.id')->get();

        return view('ams::livewire.chart-of-accounts.listing',compact('coa'));
    }

    public function changeStatus($id){
        $account = ChartOfAccount::find($id);
        if($account->status == 't'){
            $account->update([
                'status' => 'f'
            ]);
        } else {
            $account->update([
                'status' => 't'
            ]);
        }
    }
}
