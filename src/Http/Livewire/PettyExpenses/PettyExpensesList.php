<?php

namespace Devzone\Ams\Http\Livewire\PettyExpenses;

use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\PettyExpenses;
use Livewire\Component;

class PettyExpensesList extends Component
{
    public $type;
    public $filter = [];
    public $petty_expenses_list = [];
    public $fetch_account_heads = [];

    public function mount($type)
    {
        if (!in_array($type, ['unclaimed', 'claimed', 'approved'])) {
            return $this->redirectTo = '/accounts/petty-expenses';
        }
        $this->type = $type;
        $this->fetch_account_heads = ChartOfAccount::where('type', 'Expenses')->where('level', 5)->where('status', 't')->select('id', 'name')->get()->toArray();
        $this->search();
    }


    public function search()
    {
        $this->petty_expenses_list = PettyExpenses::from('petty_expenses as pe')
            ->join('chart_of_accounts as coa', 'coa.id', 'pe.account_head_id')
            ->when(!empty($this->filter['invoice_date']), function ($q) {
                return $q->where('pe.invoice_date', $this->filter['invoice_date']);
            })
            ->when(!empty($this->filter['name']), function ($q) {
                return $q->where('pe.name', $this->filter['name']);
            })
            ->when(!empty($this->filter['contact_no']), function ($q) {
                return $q->where('pe.contact_no', $this->filter['contact_no']);
            })
            ->when(!empty($this->filter['account_head_id']), function ($q) {
                return $q->where('pe.account_head_id', $this->filter['account_head_id']);
            })
            ->select('pe.*', 'coa.name as account_head')
            ->orderBy('pe.invoice_date', 'asc')
            ->get()->toArray();
    }

    public function render()
    {
        return view('ams::livewire.petty-expenses.petty-expenses-list');
    }
}