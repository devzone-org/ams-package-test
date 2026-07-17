<?php

namespace Devzone\Ams\Http\Livewire\AdminExpenses;

use App\Models\User;
use Devzone\Ams\Http\Traits\Searchable;
use Devzone\Ams\Models\AdminExpense;
use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddAdminExpenses extends Component
{
    use Searchable, WithFileUploads;

    public $admin_expenses = [];
    public $attachment;
    public $fetch_users = [];
    public $success;

    protected $rules = [
        'admin_expenses.expense_date' => 'required|date',
        'admin_expenses.vendor_id' => 'required|integer',
        'admin_expenses.invoice_no' => 'nullable|max:100',
        'admin_expenses.amount' => 'required|numeric|min:1',
        'admin_expenses.expense_account_id' => 'required|integer',
        'admin_expenses.description' => 'nullable',
        'admin_expenses.requisite_by' => 'nullable|integer',
        'attachment' => 'nullable',
    ];

    protected $validationAttributes = [
        'admin_expenses.expense_date' => 'Expense On Date',
        'admin_expenses.vendor_id' => 'Vendor',
        'admin_expenses.invoice_no' => 'Invoice #',
        'admin_expenses.amount' => 'Amount',
        'admin_expenses.expense_account_id' => 'Expense on A/C Of',
        'admin_expenses.description' => 'Description',
        'admin_expenses.requisite_by' => 'Requisite By',
        'attachment' => 'Attachment',
    ];

    public function mount()
    {
        // users table differs per host app: only filter on columns that exist.
        $this->fetch_users = User::select('id', 'name')
            ->when(Schema::hasColumn('users', 'type'), function ($q) {
                return $q->where('type', 'admin');
            })
            ->when(Schema::hasColumn('users', 'status'), function ($q) {
                return $q->where('status', 't');
            })
            ->orderBy('name')->get()->toArray();
    }

    public function save()
    {
        $this->validate();
        try {
            if (!Auth::user()->can('3.add.admin-expenses')) {
                throw new \Exception(env('PERMISSION_ERROR'));
            }

            $data = $this->admin_expenses;
            unset($data['expense_account_name']);

            $exists = ChartOfAccount::where('id', $data['expense_account_id'])->exists();
            if (!$exists) {
                throw new \Exception('Expense account not found.');
            }

            if (!empty($this->attachment)) {
                $data['attachment'] = $this->attachment->storePublicly(config('app.aws_folder') . 'admin_expenses', 's3');
            }

            $data['added_by'] = Auth::id();
            $data['status'] = 'unclaimed';

            AdminExpense::create($data);
            $this->success = 'Admin Expense Added Successfully';
            $this->clear();
        } catch (\Exception $ex) {
            $this->addError('error', $ex->getMessage());
        }
    }

    public function clear()
    {
        $this->resetErrorBag();
        $this->reset('admin_expenses', 'attachment');
    }

    public function render()
    {
        return view('ams::livewire.admin-expenses.add-admin-expenses');
    }
}
