<?php

namespace Devzone\Ams\Http\Livewire\AdminExpenses;

use Devzone\Ams\Models\AdminExpense;
use Livewire\Component;

class StatementAdminExpenses extends Component
{
    public function render()
    {
        // all claim-in-progress expenses, grouped so one code = one statement table
        $groups = AdminExpense::leftJoin('acc_vendors as v', 'v.id', '=', 'admin_expenses.acc_vendor_id')
            ->leftJoin('chart_of_accounts as coa', 'coa.id', '=', 'admin_expenses.expense_account_id')
            ->leftJoin('users as au', 'au.id', '=', 'admin_expenses.added_by')
            ->leftJoin('users as cp', 'cp.id', '=', 'admin_expenses.claim_in_progress_by')
            ->where('admin_expenses.status', 'claim-in-progress')
            ->select('admin_expenses.*', 'v.business_name as vendor_name', 'coa.name as account_head',
                'au.name as added_by_name', 'cp.name as claim_in_progress_by_name')
            ->orderBy('admin_expenses.code', 'desc')
            ->orderBy('admin_expenses.expense_date', 'asc')
            ->get()
            ->groupBy('code');

        return view('ams::livewire.admin-expenses.statement-admin-expenses', ['groups' => $groups]);
    }
}
