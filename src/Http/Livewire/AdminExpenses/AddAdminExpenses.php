<?php

namespace Devzone\Ams\Http\Livewire\AdminExpenses;

use App\Models\User;
use Devzone\Ams\Http\Traits\Searchable;
use Devzone\Ams\Http\Traits\UserSearchable;
use Devzone\Ams\Http\Traits\VendorSearchable;
use Devzone\Ams\Models\AccVendor;
use Devzone\Ams\Models\AdminExpense;
use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddAdminExpenses extends Component
{
    use Searchable, VendorSearchable, UserSearchable, WithFileUploads;

    public $admin_expenses = [];
    public $attachment;
    public $success;

    public $is_edit = false;
    public $is_view = false;

    public $new_vendor = [];

    protected $rules = [
        'admin_expenses.expense_date' => 'required|date',
        'admin_expenses.acc_vendor_id' => 'required|integer',
        'admin_expenses.invoice_no' => 'nullable|max:100',
        'admin_expenses.amount' => 'required|numeric|min:1',
        'admin_expenses.expense_account_id' => 'required|integer',
        'admin_expenses.description' => 'nullable',
        'admin_expenses.requisite_by' => 'nullable|integer',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:200',
    ];

    protected $validationAttributes = [
        'admin_expenses.expense_date' => 'Expense Date',
        'admin_expenses.acc_vendor_id' => 'Vendor',
        'admin_expenses.invoice_no' => 'Invoice #',
        'admin_expenses.amount' => 'Amount',
        'admin_expenses.expense_account_id' => 'Expense on A/C Of',
        'admin_expenses.description' => 'Description',
        'admin_expenses.requisite_by' => 'Requisite By',
        'attachment' => 'Attachment',
    ];

    public function mount($id = null)
    {
        if (!empty($id)) {
            $found = AdminExpense::find($id);
            if (empty($found)) {
                return $this->redirectTo = '/accounts/admin-expenses/list';
            }
            $this->admin_expenses = $found->toArray();
            unset($this->admin_expenses['created_at'], $this->admin_expenses['updated_at'], $this->admin_expenses['deleted_at']);
            $this->admin_expenses['vendor_name'] = AccVendor::where('id', $found->acc_vendor_id)->value('business_name');
            $this->admin_expenses['expense_account_name'] = ChartOfAccount::where('id', $found->expense_account_id)->value('name');
            $this->admin_expenses['requisite_by_name'] = User::where('id', $found->requisite_by)->value('name');
            $this->is_edit = true;
            // once claimed the record is locked: open it read-only
            $this->is_view = $found->status != 'unclaimed';
        }
    }

    public function createVendor()
    {
        if ($this->is_view) {
            return;
        }
        // opened from inside the vendor search modal: close that first
        $this->vendorReset();
        $this->resetErrorBag();
        $this->reset('new_vendor');
    }

    public function saveVendor()
    {
        $this->validate([
            'new_vendor.business_name' => 'required|max:100',
            'new_vendor.business_address' => 'nullable|max:255',
            'new_vendor.contact_no' => 'nullable|max:20',
            'new_vendor.owner_name' => 'nullable|max:100',
        ], [], [
            'new_vendor.business_name' => 'Business Name',
            'new_vendor.business_address' => 'Business Address',
            'new_vendor.contact_no' => 'Contact No.',
            'new_vendor.owner_name' => 'Owner Name',
        ]);

        try {
            $vendor = AccVendor::create($this->new_vendor);
            // a freshly created vendor is what they wanted: select it straight away
            $this->admin_expenses['acc_vendor_id'] = $vendor->id;
            $this->admin_expenses['vendor_name'] = $vendor->business_name;
            $this->closeVendorCreate();
        } catch (\Exception $ex) {
            $this->addError('error', $ex->getMessage());
        }
    }

    public function closeVendorCreate()
    {
        $this->resetErrorBag();
        $this->reset('new_vendor');
        $this->dispatchBrowserEvent('close-vendor-create-modal');
    }

    public function save()
    {
        $this->success = null;
        if ($this->is_view) {
            return;
        }

        $this->validate();
        try {
            // authorise before touching S3, otherwise a rejected save still uploads
            $permission = $this->is_edit ? '3.update.admin-expenses.unclaimed' : '3.add.admin-expenses';
            if (!Auth::user()->can($permission)) {
                throw new \Exception(env('PERMISSION_ERROR'));
            }

            $data = $this->admin_expenses;
            unset($data['expense_account_name'], $data['vendor_name'], $data['requisite_by_name']);

            $exists = ChartOfAccount::where('id', $data['expense_account_id'])->exists();
            if (!$exists) {
                throw new \Exception('Expense on A/C Of not found.');
            }

            if (!empty($this->attachment)) {
                $data['attachment'] = $this->attachment->storePublicly(config('app.aws_folder') . 'admin_expenses', 's3');
            }

            if (!$this->is_edit) {
                $data['added_by'] = Auth::id();
                $data['status'] = 'unclaimed';

                AdminExpense::create($data);
                $this->success = 'Admin Expense Added Successfully';
                $this->clear();
            } else {
                $found = AdminExpense::find($this->admin_expenses['id']);
                if (empty($found)) {
                    throw new \Exception('No Record Found.');
                }

                if ($found->status != 'unclaimed') {
                    throw new \Exception("This record has already been claimed. You can't edit.");
                }

                // status / added_by are never changed from this form
                unset($data['status'], $data['added_by']);
                $data['updated_by'] = Auth::id();

                $found->update($data);
                $this->success = 'Admin Expense Updated Successfully';
            }
        } catch (\Exception $ex) {
            $this->addError('error', $ex->getMessage());
        }
    }

    public function clear()
    {
        $this->resetErrorBag();
        if (!empty($this->admin_expenses['id'])) {
            $id = $this->admin_expenses['id'];
        }
        $this->reset('admin_expenses', 'attachment');
        if (!empty($id)) {
            $this->admin_expenses['id'] = $id;
        }
        $this->attachment = null;
    }

    public function render()
    {
        return view('ams::livewire.admin-expenses.add-admin-expenses');
    }
}
