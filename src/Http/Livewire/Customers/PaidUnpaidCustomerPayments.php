<?php

namespace Devzone\Ams\Http\Livewire\Customers;

use Devzone\Ams\Models\AmsCustomer;
use Devzone\Ams\Models\AmsCustomerPayment;
use Carbon\Carbon;
use Devzone\Ams\Models\Ledger;
use Livewire\Component;
use Illuminate\Validation\Rule;

class PaidUnpaidCustomerPayments extends Component
{
    public $redirect_back = false;
    public $customer_list = [];
    public $data = [];
    public $months_array = [];
    public $selected_months = [];
    public $success;

    public function mount()
    {
        if (env('AMS_CUSTOMER', false) !== true){
            $this->redirect_back = true;
        }
        $this->customer_list = AmsCustomer::whereNotNull('account_id')->select('account_id as id', 'name')->get()->toArray();
    }

    public function checkForRedirect()
    {
        if($this->redirect_back){
            return redirect()->to('/accounts');
        }
    }

    public function updatedDataType($val)
    {
        $this->months_array = [];
        if(!empty($val)){
             if($val == 'paid'){
                 $today = Carbon::today();
                 $this->months_array = [];
                 // Get last 6 months with year
                 for ($i = 6; $i > 0; $i--) {
                     $this->months_array[$today->copy()->subMonths($i)->format('Y-m')] = $today->copy()->subMonths($i)->format('F Y');
                 }
                 // Add current month
                 $this->months_array[$today->format('Y-m')] = $today->format('F Y');

                 // Get next 6 months with year
                 for ($i = 1; $i <= 6; $i++) {
                     $this->months_array[$today->copy()->addMonths($i)->format('Y-m')] = $today->copy()->addMonths($i)->format('F Y');
                 }
             } elseif($val == 'un-paid') {
                 $this->data['voucher_no'] = '';
                 if(!empty($this->data['customer_id'])){
                     $ams_payment_data = AmsCustomerPayment::where('customer_account_id', $this->data['customer_id'])->where('temp_voucher', 'f')->pluck('month')->toArray();
                     sort($ams_payment_data);
                     foreach($ams_payment_data as $month){
                         $this->months_array[$month] = date('F Y', strtotime($month));
                     }
                 }
             }
        }else{
            $this->data['voucher_no'] = '';
        }
    }

    public function updatedDataCustomerId($val)
    {
        $this->selected_months = [];
        $this->months_array = [];
        $this->data['type'] = '';
    }

    public function markPaidOrUnpaid()
    {
        $this->validate([
            'data.customer_id' => 'required',
            'data.type' => 'required',
            'data.voucher_no' => 'nullable',
            'selected_months' => 'required',
        ], [
            'data.customer_id' => 'Customer',
            'data.type' => 'Type',
            'selected_months' => 'Months',
        ]);

        try {
            if ($this->data['type'] == 'paid') {
                if(!empty($this->data['voucher_no'])){
                    $voucher_exists = Ledger::where('voucher_no', $this->data['voucher_no'])->exists();

                    if(!$voucher_exists){
                        throw new \Exception('Voucher # not found');
                    }
                }

                foreach($this->selected_months as $month){
                    AmsCustomerPayment::create([
                       'customer_account_id' => $this->data['customer_id'],
                       'month' => $month,
                       'voucher_no' => !empty($this->data['voucher_no']) ? $this->data['voucher_no'] : null,
                        'temp_voucher' => 'f',
                    ]);
                }

                $this->success = "Paid successfully.";
                $this->data = [];
                $this->selected_months = [];
                $this->months_array = [];
            } elseif ($this->data['type'] == 'un-paid') {
                AmsCustomerPayment::where('customer_account_id', $this->data['customer_id'])->where('temp_voucher', 'f')->whereIn('month', $this->selected_months)->delete();
                $this->success = "Unpaid successfully.";
                $this->data = [];
                $this->selected_months = [];
                $this->months_array = [];
            } else {
                throw new \Exception('Unknown Type.');
            }
        }catch(\Exception $e){
            $this->addError('exception', $e->getMessage());
        }
    }

    public function render()
    {
        return view('ams::livewire.customers.paid-unpaid-customer-payments');
    }
}