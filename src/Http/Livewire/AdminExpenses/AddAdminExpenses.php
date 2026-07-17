<?php

namespace Devzone\Ams\Http\Livewire\AdminExpenses;

use App\Models\User;
use Devzone\Ams\Http\Traits\Searchable;
use Devzone\Ams\Models\AccVendor;
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

    public $is_edit = false;
    public $is_view = false;

    public $vendor_modal = false;
    public $vendor_create = false;
    public $vendor_search = '';
    public $vendors = [];
    public $new_vendor = [];

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

    public function mount($id = null)
    {
        if (!empty($id)) {
            $found = AdminExpense::find($id);
            if (empty($found)) {
                return $this->redirectTo = '/accounts/admin-expenses/list';
            }
            $this->admin_expenses = $found->toArray();
            unset($this->admin_expenses['created_at'], $this->admin_expenses['updated_at'], $this->admin_expenses['deleted_at']);
            $this->admin_expenses['vendor_name'] = AccVendor::where('id', $found->vendor_id)->value('business_name');
            $this->admin_expenses['expense_account_name'] = ChartOfAccount::where('id', $found->expense_account_id)->value('name');
            $this->is_edit = true;
            // once claimed the record is locked: open it read-only
            $this->is_view = $found->status != 'unclaimed';
        }

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

    public function openVendorModal()
    {
        if ($this->is_view) {
            return;
        }
        $this->resetErrorBag();
        $this->vendor_create = false;
        $this->vendor_search = '';
        $this->reset('new_vendor');
        $this->searchVendors();
        $this->vendor_modal = true;
        if (env('AMS_BOOTSTRAP') == 'true') {
            $this->dispatchBrowserEvent('open-vendor-modal');
        }
    }

    public function updatedVendorSearch()
    {
        $this->searchVendors();
    }

    public function searchVendors()
    {
        $this->vendors = AccVendor::when(!empty($this->vendor_search), function ($q) {
            return $q->where(function ($q) {
                return $q->orWhere('business_name', 'LIKE', '%' . $this->vendor_search . '%')
                    ->orWhere('owner_name', 'LIKE', '%' . $this->vendor_search . '%')
                    ->orWhere('contact_no', 'LIKE', '%' . $this->vendor_search . '%');
            });
        })->orderBy('business_name')->limit(50)->get()->toArray();
    }

    public function selectVendor($id)
    {
        $vendor = AccVendor::find($id);
        if (empty($vendor)) {
            $this->addError('error', 'Vendor not found.');
            return;
        }
        $this->admin_expenses['vendor_id'] = $vendor->id;
        $this->admin_expenses['vendor_name'] = $vendor->business_name;
        $this->closeVendorModal();
    }

    public function createVendor()
    {
        $this->resetErrorBag();
        $this->reset('new_vendor');
        // carry whatever they typed in the search into the form
        $this->new_vendor['business_name'] = $this->vendor_search;
        $this->vendor_create = true;
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
            $this->selectVendor($vendor->id);
        } catch (\Exception $ex) {
            $this->addError('error', $ex->getMessage());
        }
    }

    public function closeVendorModal()
    {
        $this->vendor_modal = false;
        $this->vendor_create = false;
        $this->vendor_search = '';
        $this->vendors = [];
        $this->reset('new_vendor');
        if (env('AMS_BOOTSTRAP') == 'true') {
            $this->dispatchBrowserEvent('close-vendor-modal');
        }
    }

    public function save()
    {
        if ($this->is_view) {
            return;
        }

        $this->validate();
        try {
            $data = $this->admin_expenses;
            unset($data['expense_account_name'], $data['vendor_name']);

            $exists = ChartOfAccount::where('id', $data['expense_account_id'])->exists();
            if (!$exists) {
                throw new \Exception('Expense account not found.');
            }

            if (!empty($this->attachment)) {
                $data['attachment'] = $this->attachment->storePublicly(config('app.aws_folder') . 'admin_expenses', 's3');
            }

            if (!$this->is_edit) {
                if (!Auth::user()->can('3.add.admin-expenses')) {
                    throw new \Exception(env('PERMISSION_ERROR'));
                }

                $data['added_by'] = Auth::id();
                $data['status'] = 'unclaimed';

                AdminExpense::create($data);
                $this->success = 'Admin Expense Added Successfully';
                $this->clear();
            } else {
                if (!Auth::user()->can('3.update.admin-expenses.unclaimed')) {
                    throw new \Exception(env('PERMISSION_ERROR'));
                }

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
    }

    public function render()
    {
        return view('ams::livewire.admin-expenses.add-admin-expenses');
    }
}
