<?php

namespace Devzone\Ams\Http\Livewire\ChartOfAccount;

use Devzone\Ams\Models\ChartOfAccount;
use Livewire\Component;

class Listing extends Component
{
    public $type;
    public function render()
    {
        $coa = ChartOfAccount::when(!empty($this->type),function($q){
            return $q->where('type',$this->type);
        })->get();

        return view('ams::livewire.chart-of-accounts.listing',compact('coa'));
    }
}
