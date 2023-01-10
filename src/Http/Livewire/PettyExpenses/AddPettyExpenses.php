<?php

namespace Devzone\Ams\Http\Livewire\PettyExpenses;

use Livewire\Component;

class AddPettyExpenses extends Component
{
    public $petty_expenses = [];

    public function render()
    {
        return view('ams::livewire.petty-expenses.add-petty-expenses');
    }
}