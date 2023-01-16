<?php

namespace Devzone\Ams\Http\Livewire\PettyExpenses;

use Illuminate\Support\Facades\Auth;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\PettyExpenses;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddPettyExpenses extends Component
{
    use WithFileUploads;

    public $petty_expenses = [];
    public $attachment;

    protected $rules = [
        'petty_expenses.invoice_date' => 'required|date',
        'petty_expenses.vendor_name' => 'required',
        'petty_expenses.vendor_contact_no' => 'required|max:15',
        'attachment' => 'nullable',
        'petty_expenses.account_head_id' => 'required|integer',
        'petty_expenses.amount' => 'required|numeric|min:1',
        'petty_expenses.description' => 'required',
    ];

    protected $validationAttributes = [
        'petty_expenses.invoice_date' => 'Invoice Date',
        'petty_expenses.vendor_name' => 'Vendor Name',
        'petty_expenses.vendor_contact_no' => 'Vendor Contact #',
        'attachment' => 'Attachment',
        'petty_expenses.account_head_id' => 'Account Head',
        'petty_expenses.amount' => 'Amount',
        'petty_expenses.description' => 'Description',
    ];

    public $fetch_account_heads = [];
    public $success;
    public $is_edit = false;

    public function mount($id)
    {
        if (!empty($id)) {
            $found = PettyExpenses::find($id);
            if (empty($found)) {
                return $this->redirectTo = '/accounts/petty-expenses';
            }
            $this->petty_expenses = $found->toArray();
            unset($this->petty_expenses['created_at'], $this->petty_expenses['updated_at']);
            $this->is_edit = true;
        }
        $this->fetch_account_heads = ChartOfAccount::where('type', 'Expenses')->where('level', 5)->where('status', 't')->select('id', 'name')->get()->toArray();
    }


    public function save()
    {
        $this->validate();
        try {
            if (!empty($this->attachment)) {
                $this->petty_expenses['attachment'] = $this->attachment->storePublicly(config('app.aws_folder') . 'petty_expenses', 's3');
            }

            $exists = ChartOfAccount::where('id', $this->petty_expenses['account_head_id'])->exists();
            if (!$exists) {
                throw new \Exception('Account Head not found.');
            }

            $this->petty_expenses['created_by'] = Auth::id();
            if (!$this->is_edit) {
                if (!Auth::user()->can('3.add.petty-expenses')) {
                    throw new \Exception(env('PERMISSION_ERROR'));
                }

                $account_id = auth()->user()->account_id;
                if (empty($account_id)) {
                    throw new \Exception('account not found.');
                }

                $exists = ChartOfAccount::where('id', $account_id)->exists();
                if (!$exists) {
                    throw new \Exception('account not found.');
                }

                $this->petty_expenses['paid_by_account_id'] = $account_id;

                PettyExpenses::create($this->petty_expenses);
                $this->success = 'Record Created Successfully';
                $this->clear();
            } else {
                if (!Auth::user()->can('3.edit.petty-expenses')) {
                    throw new \Exception(env('PERMISSION_ERROR'));
                }
                $found = PettyExpenses::find($this->petty_expenses['id']);
                if (empty($found)) {
                    throw new \Exception('No Record Found.');
                }

                if (!empty($found['claimed_by'])) {
                    throw new \Exception("This record has already been claimed.You can't edit.");
                }

                $found->update($this->petty_expenses);
                $this->success = 'Record Updated Successfully.';
            }
        } catch (\Exception $ex) {
            $this->addError('error', $ex->getMessage());
        }
    }

    public function clear()
    {
        $this->resetErrorBag();
        if (!empty($this->petty_expenses['id'])) {
            $id = $this->petty_expenses['id'];
        };
        $this->reset('petty_expenses');
        if (!empty($id)) {
            $this->petty_expenses['id'] = $id;
        }
        $this->petty_expenses['attachment'] = null;
    }


    public function render()
    {
        return view('ams::livewire.petty-expenses.add-petty-expenses');
    }
}