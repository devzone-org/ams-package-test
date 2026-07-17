<?php

namespace Devzone\Ams\Http\Livewire\AdminExpenses;

use Devzone\Ams\Models\AdminExpense;
use Devzone\Ams\Models\ChartOfAccount;
use Livewire\Component;

class AdminExpensesList extends Component
{
    public $filter = [];
    public $admin_expenses_list = [];
    public $fetch_account_heads = [];
    public $fetch_added_by = [];

    public function mount()
    {
        $this->fetch_account_heads = ChartOfAccount::where('type', 'Expenses')->where('level', 5)->where('status', 't')
            ->select('id', 'name')->get()->toArray();

        // only users who have actually added an expense
        $this->fetch_added_by = AdminExpense::join('users as u', 'u.id', '=', 'admin_expenses.added_by')
            ->select('u.id', 'u.name')->distinct()->orderBy('u.name')->get()->toArray();

        $this->search();
    }

    public function search()
    {
        // admin_expenses is not aliased: the SoftDeletes scope qualifies admin_expenses.deleted_at
        $this->admin_expenses_list = AdminExpense::leftJoin('acc_vendors as v', 'v.id', '=', 'admin_expenses.vendor_id')
            ->leftJoin('chart_of_accounts as coa', 'coa.id', '=', 'admin_expenses.expense_account_id')
            ->leftJoin('users as au', 'au.id', '=', 'admin_expenses.added_by')
            ->when(!empty($this->filter['expense_date']), function ($q) {
                return $q->where('admin_expenses.expense_date', $this->filter['expense_date']);
            })
            ->when(!empty($this->filter['vendor']), function ($q) {
                return $q->where('v.business_name', 'LIKE', '%' . $this->filter['vendor'] . '%');
            })
            ->when(!empty($this->filter['expense_account_id']), function ($q) {
                return $q->where('admin_expenses.expense_account_id', $this->filter['expense_account_id']);
            })
            ->when(!empty($this->filter['amount']), function ($q) {
                return $q->where('admin_expenses.amount', $this->filter['amount']);
            })
            ->when(!empty($this->filter['added_by']), function ($q) {
                return $q->where('admin_expenses.added_by', $this->filter['added_by']);
            })
            ->when(!empty($this->filter['added_at']), function ($q) {
                return $q->whereDate('admin_expenses.created_at', $this->filter['added_at']);
            })
            ->when(!empty($this->filter['status']), function ($q) {
                return $q->where('admin_expenses.status', $this->filter['status']);
            })
            ->select('admin_expenses.*', 'v.business_name as vendor_name', 'v.contact_no as vendor_contact_no',
                'coa.name as account_head', 'au.name as added_by_name')
            ->orderBy('admin_expenses.expense_date', 'desc')
            ->get()->toArray();
    }

    public function clear()
    {
        $this->reset('filter');
        $this->search();
    }

    public function render()
    {
        return view('ams::livewire.admin-expenses.admin-expenses-list');
    }
}
