<?php

namespace Devzone\Ams\Http\Livewire\AdminExpenses;

use Carbon\Carbon;
use Devzone\Ams\Models\AdminExpense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ClaimAdminExpenses extends Component
{
    public $admin_expenses_list = [];
    public $checked_admin_expenses = [];
    public $checked_all;
    public $success;

    /**
     * The ticked rows, for the "Selected Bills" modal.
     */
    public function getSelectedExpensesProperty()
    {
        return collect($this->admin_expenses_list)
            ->whereIn('id', array_keys(array_filter($this->checked_admin_expenses)))
            ->values()->toArray();
    }

    public function removeSelected($id)
    {
        unset($this->checked_admin_expenses[$id]);
        $this->checked_all = false;
    }

    public function mount()
    {
        $this->search();
    }

    public function updatedCheckedAdminExpenses()
    {
        $this->checked_all = count(array_filter($this->checked_admin_expenses)) == count($this->admin_expenses_list);
    }

    public function updatedCheckedAll($checked)
    {
        if ($checked) {
            $this->checked_admin_expenses = array_fill_keys(array_column($this->admin_expenses_list, 'id'), true);
        } else {
            $this->checked_admin_expenses = [];
        }
    }

    public function search()
    {
        // admin_expenses is not aliased: the SoftDeletes scope qualifies admin_expenses.deleted_at
        $this->admin_expenses_list = AdminExpense::leftJoin('acc_vendors as v', 'v.id', '=', 'admin_expenses.vendor_id')
            ->leftJoin('chart_of_accounts as coa', 'coa.id', '=', 'admin_expenses.expense_account_id')
            ->leftJoin('users as au', 'au.id', '=', 'admin_expenses.added_by')
            ->where('admin_expenses.status', 'unclaimed')
            ->select('admin_expenses.*', 'v.business_name as vendor_name', 'coa.name as account_head',
                'au.name as added_by_name')
            ->orderBy('admin_expenses.expense_date', 'asc')
            ->get()->toArray();
    }

    public function claim()
    {
        try {
            if (!Auth::user()->can('3.claim.admin-expenses')) {
                throw new \Exception(env('PERMISSION_ERROR'));
            }

            DB::beginTransaction();

            $ids = array_keys(array_filter($this->checked_admin_expenses));
            if (empty($ids)) {
                throw new \Exception('Please select any record to proceed.');
            }

            // conditional update so two concurrent claimers can't both flip the same row
            $claimed = AdminExpense::whereIn('id', $ids)->where('status', 'unclaimed')->update([
                'status' => 'claimed',
                'status_changed_by' => auth()->id(),
                'status_changed_at' => Carbon::now()->toDateTimeString(),
            ]);

            if ($claimed != count($ids)) {
                throw new \Exception('One or more admin expenses were removed or already claimed. Please select again.');
            }

            $this->success = 'Admin Expenses Claimed Successfully.';
            $this->reset('checked_admin_expenses', 'checked_all');
            $this->dispatchBrowserEvent('close-selected-modal');
            $this->search();

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->search();
            $this->addError('error', $ex->getMessage());
        }
    }

    public function render()
    {
        return view('ams::livewire.admin-expenses.claim-admin-expenses');
    }
}
