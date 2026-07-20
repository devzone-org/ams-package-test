<?php

namespace Devzone\Ams\Http\Livewire\AdminExpenses;

use Devzone\Ams\Models\AdminExpense;
use Livewire\Component;

class AdminExpensesList extends Component
{
    public $filter = [];
    public $admin_expenses_list = [];
    public $success;
    public $delete_modal = false;
    public $delete_id;
    public $delete_modal_msg;

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
            ->when(!empty($this->filter['expense_date_from']), function ($q) {
                return $q->whereDate('admin_expenses.expense_date', '>=', $this->filter['expense_date_from']);
            })
            ->when(!empty($this->filter['expense_date_to']), function ($q) {
                return $q->whereDate('admin_expenses.expense_date', '<=', $this->filter['expense_date_to']);
            })
            ->when(!empty($this->filter['vendor']), function ($q) {
                return $q->where('v.business_name', 'LIKE', '%' . $this->filter['vendor'] . '%');
            })
            ->when(!empty($this->filter['expense_account']), function ($q) {
                return $q->where('coa.name', 'LIKE', '%' . $this->filter['expense_account'] . '%');
            })
            ->when(!empty($this->filter['amount']), function ($q) {
                return $q->where('admin_expenses.amount', $this->filter['amount']);
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

    public function openDeleteModal($id)
    {
        $this->resetErrorBag();
        $this->success = '';

        try {
            if (auth()->user()->cannot('3.delete.admin-expenses.unclaimed')) {
                throw new \Exception("You don't have permission to perform this action.");
            }

            $found = AdminExpense::select('id', 'status')->find($id);
            if (empty($found)) {
                throw new \Exception('Admin expense not found.');
            }
            if ($found->status != 'unclaimed') {
                throw new \Exception("This record has already been claimed. You can't delete it.");
            }

            $this->delete_modal = true;
            $this->delete_id = $id;
            $this->delete_modal_msg = 'delete this admin expense';

            if (env('AMS_BOOTSTRAP') == 'true') {
                $this->dispatchBrowserEvent('open-delete-modal');
            }
        } catch (\Exception $ex) {
            $this->addError('error', $ex->getMessage());
        }
    }

    public function closeDeleteModal()
    {
        $this->resetErrorBag();
        $this->reset('success', 'delete_modal', 'delete_id', 'delete_modal_msg');

        if (env('AMS_BOOTSTRAP') == 'true') {
            $this->dispatchBrowserEvent('close-delete-modal');
        }
    }

    public function deleteRecord()
    {
        try {
            if (auth()->user()->cannot('3.delete.admin-expenses.unclaimed')) {
                throw new \Exception("You don't have permission to perform this action.");
            }

            $found = AdminExpense::find($this->delete_id);
            if (empty($found)) {
                throw new \Exception('Admin expense not found.');
            }
            // re-check: it could have been claimed after the modal was opened
            if ($found->status != 'unclaimed') {
                throw new \Exception("This record has already been claimed. You can't delete it.");
            }

            $found->delete();
            $this->success = 'Admin expense deleted successfully.';
            $this->reset('delete_modal', 'delete_id', 'delete_modal_msg');

            if (env('AMS_BOOTSTRAP') == 'true') {
                $this->dispatchBrowserEvent('close-delete-modal');
            }

            $this->search();
        } catch (\Exception $ex) {
            $this->addError('error', $ex->getMessage());
        }
    }

    public function render()
    {
        return view('ams::livewire.admin-expenses.admin-expenses-list');
    }
}
