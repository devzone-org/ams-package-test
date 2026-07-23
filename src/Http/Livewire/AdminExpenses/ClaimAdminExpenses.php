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
        $this->checked_admin_expenses = [];
        if ($checked) {
            $this->checked_admin_expenses = array_fill_keys(array_column($this->admin_expenses_list, 'id'), true);
        }
    }

    public function search()
    {
        // admin_expenses is not aliased: the SoftDeletes scope qualifies admin_expenses.deleted_at
        $this->admin_expenses_list = AdminExpense::leftJoin('acc_vendors as v', 'v.id', '=', 'admin_expenses.acc_vendor_id')
            ->leftJoin('chart_of_accounts as coa', 'coa.id', '=', 'admin_expenses.expense_account_id')
            ->leftJoin('users as au', 'au.id', '=', 'admin_expenses.added_by')
            ->where('admin_expenses.status', 'unclaimed')
            ->select('admin_expenses.*', 'v.business_name as vendor_name', 'coa.name as account_head',
                'au.name as added_by_name')
            ->orderBy('admin_expenses.expense_date', 'asc')
            ->get()->toArray();
    }

    public function proceedClaim()
    {
        try {
            if (!Auth::user()->can('3.claim.admin-expenses')) {
                throw new \Exception(env('PERMISSION_ERROR'));
            }

            $ids = array_keys(array_filter($this->checked_admin_expenses));
            if (empty($ids)) {
                throw new \Exception('Please select any record to proceed.');
            }

            DB::transaction(function () use ($ids) {
                // lock the rows so two concurrent claimers can't grab the same ones
                $expenses = AdminExpense::whereIn('id', $ids)
                    ->where('status', 'unclaimed')
                    ->lockForUpdate()->get();

                if ($expenses->count() != count($ids)) {
                    throw new \Exception('One or more expenses were removed or already in a claim. Please select again.');
                }

                // one shared code for the whole claim batch; next number = highest so far + 1
                // ponytail: max()+1 under the row lock above; fine for expense volumes.
                $next = (int) AdminExpense::max(DB::raw('CAST(SUBSTRING(code, 5) AS UNSIGNED)')) + 1;
                $code = 'AEC-' . str_pad($next, 3, '0', STR_PAD_LEFT);

                AdminExpense::whereIn('id', $expenses->pluck('id'))->update([
                    'code' => $code,
                    'status' => 'claim-in-progress',
                    'claim_in_progress_by' => auth()->id(),
                    'claim_in_progress_at' => Carbon::now()->toDateTimeString(),
                ]);
            });

            $this->success = 'Selected expenses moved to claim in progress.';
            $this->reset('checked_admin_expenses', 'checked_all');
            $this->dispatchBrowserEvent('close-selected-modal');
            $this->search();
        } catch (\Exception $ex) {
            $this->search();
            $this->addError('error', $ex->getMessage());
        }
    }

    public function claim()
    {
        try {
            throw new \Exception("Under Development.");

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
                'claimed_by' => auth()->id(),
                'claimed_at' => Carbon::now()->toDateTimeString(),
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
