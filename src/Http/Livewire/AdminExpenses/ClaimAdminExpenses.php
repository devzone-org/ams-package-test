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
    public $selected_expenses = [];
    public $success;

    public function mount()
    {
        $this->search();
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

            $ids = array_unique(array_filter($this->selected_expenses));
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
            $this->reset('selected_expenses');
            $this->search();
            $this->dispatchBrowserEvent('resetCheckboxes');

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
