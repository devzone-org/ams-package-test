<?php

namespace Devzone\Ams\Http\Livewire\PettyExpenses;

use http\Env\Request;
use Livewire\Component;

class Tab extends Component
{
    public function render()
    {
        return view('ams::livewire.petty-expenses.tab');
    }
}