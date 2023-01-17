<?php

namespace Devzone\Ams\Http\Livewire\PettyExpenses;

use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\PettyExpenses;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PettyExpensesList extends Component
{
    public $type;
    public $filter = [];
    public $petty_expenses_list = [];
    public $fetch_account_heads = [];
    public $delete_modal = false;
    public $delete_id;
    public $delete_modal_msg;
    public $success;

    public $checked_petty_expenses = [];
    public $checked_all;

    public function mount()
    {
        $this->fetch_account_heads = ChartOfAccount::where('type', 'Expenses')->where('level', 5)->where('status', 't')->select('id', 'name')->get()->toArray();
        $this->search();
    }

    public function updatedCheckedPettyExpenses()
    {
        if (count(array_filter($this->checked_petty_expenses)) == count($this->petty_expenses_list)) {
            $this->checked_all = true;
        } else {
            $this->checked_all = false;
        }
    }

    public function updatedCheckedAll($checked)
    {
        if ($checked) {
            $this->checked_petty_expenses = array_fill_keys(array_column($this->petty_expenses_list, 'id'), true);
        } else {
            $this->checked_petty_expenses = [];
        }
    }


    public function search()
    {
        $this->petty_expenses_list = PettyExpenses::join('chart_of_accounts as coa', 'coa.id', 'petty_expenses.account_head_id')
            ->join('chart_of_accounts as ecoa', 'ecoa.id', 'petty_expenses.paid_by_account_id')
            ->whereNull('petty_expenses.claimed_by')
            ->whereNull('petty_expenses.approved_by')
            ->when(!empty($this->filter['invoice_date']), function ($q) {
                return $q->where('petty_expenses.invoice_date', $this->filter['invoice_date']);
            })
            ->when(!empty($this->filter['name']), function ($q) {
                return $q->where('petty_expenses.vendor_name', 'Like', '%' . $this->filter['name'] . '%');
            })
            ->when(!empty($this->filter['contact_no']), function ($q) {
                return $q->where('petty_expenses.vendor_contact_no', 'Like', '%' . $this->filter['contact_no'] . '%');
            })
            ->when(!empty($this->filter['account_head_id']), function ($q) {
                return $q->where('petty_expenses.account_head_id', $this->filter['account_head_id']);
            })
            ->select('petty_expenses.*', 'coa.name as account_head', 'ecoa.name as expense_head')
            ->orderBy('petty_expenses.invoice_date', 'asc')
            ->get()->toArray();

        $this->checked_all = false;
        $this->checked_petty_expenses = [];
    }

    public function openDeleteModal($id)
    {
        $this->resetErrorBag();
        $this->success = '';
        $this->delete_modal = true;
        $this->delete_id = $id;
        $this->delete_modal_msg = 'delete this record';
    }

    public function closeDeleteModal()
    {

        $this->delete_modal = false;
        $this->delete_id = null;
        $this->delete_modal_msg = '';
    }

    public function deleteRecord()
    {
        $this->resetErrorBag();
        try {
            if (!Auth::user()->can('3.delete.petty-expenses')) {
                throw new \Exception(env('PERMISSION_ERROR'));
            }
            $record = PettyExpenses::find($this->delete_id);
            if (empty($record)) {
                throw new \Exception('Record not found.');
            }
            $record->delete();
            $this->success = "Record Deleted Successfully";
            $this->search();
            $this->closeDeleteModal();


        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }

    }

    public function claim()
    {
        try {
            if (!Auth::user()->can('3.claim.petty-expenses')) {
                throw new \Exception(env('PERMISSION_ERROR'));
            }
            DB::beginTransaction();
            if (empty(array_filter($this->checked_petty_expenses))) {
                throw new \Exception('Please select any record to proceed.');
            }

            foreach (array_keys(array_filter($this->checked_petty_expenses)) as $id) {
                $found = PettyExpenses::find($id);
                if (!empty($found['claimed_by'])) {
                    $this->search();
                    throw new \Exception('Petty expenses has already been claimed. Please select again.');
                }

                $found->update([
                    'claimed_by' => auth()->id(),
                    'claimed_at' => Carbon::now()->toDateString()
                ]);
            }

            $this->success = 'Petty Expenses Claimed Successfully.';
            $this->search();


            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->addError('error', $ex->getMessage());
        }
    }


    public function clear()
    {
        $this->reset('filter');
    }


    public function render()
    {
        return view('ams::livewire.petty-expenses.petty-expenses-list');
    }
}