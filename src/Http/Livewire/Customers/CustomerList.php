<?php

namespace Devzone\Ams\Http\Livewire\Customers;

use Devzone\Ams\Helper\Voucher;
use Devzone\Ams\Models\AmsCustomer;
use Devzone\Ams\Models\ChartOfAccount;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class CustomerList extends Component
{
    use WithPagination;
    public $customer_data = [
        'name' => '',
        'status' => 'Opportunity',
        'email' => '',
        'mobile_no' => '',
        'phone' => '',
        'city' => '',
        'address' => '',
    ];
    public $edit_customer_data = [
        'name' => '',
        'status' => 'Opportunity',
        'email' => '',
        'mobile_no' => '',
        'phone' => '',
        'city' => '',
        'address' => '',
    ];
    public $edit_modal = false;
    public $edit_id;
    public $success;
    public $redirect_back = false;

    protected $rules = [
        'customer_data.name' => 'required',
        'customer_data.mobile_no' => 'required',
        'customer_data.status' => 'required',
        'customer_data.email' => 'nullable|unique:ams_customers,email',
    ];
    protected $validationAttributes = [
        'customer_data.name' => 'Customer Name',
        'customer_data.mobile_no' => 'Mobile #',
        'customer_data.status' => 'Status',
        'customer_data.email' => 'email',
    ];
    public function mount()
    {
        if (env('AMS_CUSTOMER', false) !== true){
            $this->redirect_back = true;
        }
    }

    public function checkForRedirect()
    {
        if($this->redirect_back){
            return redirect()->to('/accounts');
        }
    }

    public function create()
    {
        $this->success = "";
        $this->validate();
        try{
            DB::beginTransaction();

            if (auth()->user()->cannot('4.add.customers')){
                throw new \Exception('You do not have permission to create customers.');
            }

            //if status selected was active then coa will be created
            $account_id = null;
            if($this->customer_data['status'] == 'Active'){
                $account_id = $this->addCoa($this->customer_data['name']);
            }
            $this->customer_data['account_id'] = $account_id;
            AmsCustomer::create([
                'name' => $this->customer_data['name'],
                'email' => !empty($this->customer_data['email']) ? $this->customer_data['email'] : null,
                'mobile_no' => $this->customer_data['mobile_no'],
                'phone' => !empty($this->customer_data['phone']) ? $this->customer_data['phone'] : null,
                'city' => !empty($this->customer_data['city']) ? $this->customer_data['city'] : null,
                'address' => !empty($this->customer_data['address']) ? $this->customer_data['address'] : null,
                'status' => $this->customer_data['status'],
                'account_id' => !empty($this->customer_data['account_id']) ? $this->customer_data['account_id'] : null,
            ]);
            DB::commit();

            $this->success = "Record has been added.";
            $this->resetCustomerData();
        }catch(\Exception $e){
            $this->addError('exception', $e->getMessage());
            DB::rollback();
        }
    }

    public function updateCustomer()
    {
        $this->validate([
            'edit_customer_data.name' => 'required',
            'edit_customer_data.mobile_no' => 'required',
            'edit_customer_data.status' => 'required',
            'edit_customer_data.email' => [
                'nullable',
                Rule::unique('ams_customers', 'email')->ignore($this->edit_id),
            ],
        ], [
            'edit_customer_data.name' => 'Customer Name',
            'edit_customer_data.mobile_no' => 'Mobile #',
            'edit_customer_data.status' => 'Status',
            'edit_customer_data.email' => 'email',
        ]);
        try {
            DB::BeginTransaction();

            if (auth()->user()->cannot('4.edit.customers')){
                throw new \Exception('You do not have permission to edit customers.');
            }

            $cus_data = AmsCustomer::find($this->edit_id);
            if(!empty($cus_data)){
                $account_id = $cus_data['account_id'];

                if(!empty($account_id)){
                    ChartOfAccount::find($account_id)->update([
                        'name' => $this->edit_customer_data['name'],
                        'status' => ($this->edit_customer_data['status'] != 'In-Active') ? 't' : 'f',
                    ]);
                }

                if($this->edit_customer_data['status'] == 'Active' && empty($account_id)){
                    $account_id = $this->addCoa($this->edit_customer_data['name']);
                }
                $this->edit_customer_data['account_id'] = $account_id;
                AmsCustomer::find($this->edit_id)->update([
                    'name' => $this->edit_customer_data['name'],
                    'email' => !empty($this->edit_customer_data['email']) ? $this->edit_customer_data['email'] : null,
                    'mobile_no' => $this->edit_customer_data['mobile_no'],
                    'phone' => !empty($this->edit_customer_data['phone']) ? $this->edit_customer_data['phone'] : null,
                    'city' => !empty($this->edit_customer_data['city']) ? $this->edit_customer_data['city'] : null,
                    'address' => !empty($this->edit_customer_data['address']) ? $this->edit_customer_data['address'] : null,
                    'status' => $this->edit_customer_data['status'],
                    'account_id' => !empty($this->edit_customer_data['account_id']) ? $this->edit_customer_data['account_id'] : null,
                ]);
            }else{
                throw new \Exception("Record not found");
            }
            DB::commit();
            $this->success = "Record has been updated.";
            $this->resetCustomerData();
            $this->edit_id = '';
            $this->edit_modal = false;
        }catch (\Exception $e){
            $this->addError('exception', $e->getMessage());
            DB::rollback();
        }
    }

    private function addCoa($name)
    {
        $find_level4_acc = ChartOfAccount::where('reference', 'ams-customers-l4')->first();
        if(empty($find_level4_acc)){
            throw new \Exception("Level 4 account not found");
        }
        $sub_account = $find_level4_acc['id'];
        $type = $find_level4_acc['type'];
        $nature = $find_level4_acc['nature'];

        if(empty($sub_account) || empty($type) || empty($nature)){
            throw new \Exception("Issue with level 4 account.");
        }

        $code = Voucher::instance()->coa()->get();
        $code = str_pad($code, 7, "0", STR_PAD_LEFT);
        $account_id = ChartOfAccount::create([
            'name' => $name,
            'type' => $type,
            'sub_account' => $sub_account,
            'level' => '5',
            'code' => $code,
            'nature' => $nature,
            'is_contra' => 'f',
            'status' => 't'
        ])->id;
        return $account_id;
    }

    private function resetCustomerData()
    {
        $this->customer_data = [
            'name' => '',
            'status' => 'Opportunity',
            'email' => '',
            'mobile_no' => '',
            'phone' => '',
            'city' => '',
            'address' => '',
        ];
        $this->edit_customer_data = [
            'name' => '',
            'status' => 'Opportunity',
            'email' => '',
            'mobile_no' => '',
            'phone' => '',
            'city' => '',
            'address' => '',
        ];
    }

    public function openEditModal($customer_id)
    {
        try{
            $this->success = '';
            $this->resetCustomerData();
            $cus_data = AmsCustomer::find($customer_id);
            if(!empty($cus_data)){
                $this->edit_id = $cus_data['id'];
                $this->edit_customer_data = [
                    'name' =>  $cus_data['name'],
                    'status' => $cus_data['status'],
                    'email' => $cus_data['email'],
                    'mobile_no' => $cus_data['mobile_no'],
                    'phone' => $cus_data['phone'],
                    'city' => $cus_data['city'],
                    'address' => $cus_data['address'],
                    'account_id' => $cus_data['account_id'],
                ];
                $this->edit_modal = true;
            }else{
                throw new \Exception("Record not found");
            }
        }catch(\Exception $e){
            $this->addError('exception', $e->getMessage());
            $this->edit_modal = false;
        }
    }

    public function render()
    {
        $customers = AmsCustomer::paginate(10);
        return view('ams::livewire.customers.customer-list', ['customers' => $customers]);
    }
}