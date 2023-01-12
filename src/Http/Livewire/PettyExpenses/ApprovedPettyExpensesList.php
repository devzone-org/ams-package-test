<?php

namespace Devzone\Ams\Http\Livewire\PettyExpenses;

use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\PettyExpenses;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApprovedPettyExpensesList extends Component
{
    public $type;
    public $filter = [];
    public $petty_expenses_list = [];
    public $fetch_account_heads = [];

    public function mount()
    {
        $this->fetch_account_heads = ChartOfAccount::where('type', 'Expenses')->where('level', 5)->where('status', 't')->select('id', 'name')->get()->toArray();
        $this->search();
    }


    public function search()
    {
        $this->petty_expenses_list = PettyExpenses::join('chart_of_accounts as coa', 'coa.id', 'petty_expenses.account_head_id')
            ->leftJoin('users as cu','cu.id','petty_expenses.claimed_by')
            ->leftJoin('users as au','au.id','petty_expenses.approved_by')
            ->whereNotNull('petty_expenses.claimed_by')->whereNotNull('petty_expenses.approved_by')
            ->when(!empty($this->filter['invoice_date']), function ($q) {
                return $q->where('petty_expenses.invoice_date', $this->filter['invoice_date']);
            })
            ->when(!empty($this->filter['name']), function ($q) {
                return $q->where('petty_expenses.name', $this->filter['name']);
            })
            ->when(!empty($this->filter['contact_no']), function ($q) {
                return $q->where('petty_expenses.contact_no', $this->filter['contact_no']);
            })
            ->when(!empty($this->filter['account_head_id']), function ($q) {
                return $q->where('petty_expenses.account_head_id', $this->filter['account_head_id']);
            })
            ->select('petty_expenses.*', 'coa.name as account_head','cu.name as claimed_by','au.name as approved_by')
            ->orderBy('petty_expenses.invoice_date', 'asc')
            ->get()->toArray();
    }


    public function render()
    {
        return view('ams::livewire.petty-expenses.approved-petty-expenses-list');
    }
}