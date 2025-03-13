<?php


namespace Devzone\Ams\Http\Livewire\Reports;

use Devzone\Ams\Models\AmsCustomer;
use Livewire\Component;

class CustomerPaymentReport extends Component
{
    public $customer_list = [], $customer_id;
    public $from_date, $to_date, $status, $payment_status;
    public $customer_payment_data = [];
    public $months_array = [];
    public $customer_list_2 = [];

    public function mount()
    {
        $this->to_date = date('Y-m');
        $this->from_date = date('Y-m');
        $this->customer_list = AmsCustomer::select('id', 'name')->get()->toArray();
        $this->fetchReport();
    }

    public function fetchReport()
    {
        $this->months_array = $this->getMonthsBetween($this->from_date, $this->to_date);

        $this->customer_list_2 = AmsCustomer::when(!empty($this->customer_id), function ($query) {
                $query->where('id', $this->customer_id);
            })
            ->when(!empty($this->status), function ($query) {
                $query->where('status', $this->status);
            })
            ->select('id', 'name', 'status')
            ->get()
            ->toArray();

        $this->customer_payment_data = AmsCustomer::leftjoin('ams_customer_payments as acp', 'acp.customer_account_id', 'ams_customers.account_id')
            ->where('acp.temp_voucher', 'f')
            ->where('acp.month', '>=', $this->from_date)
            ->where('acp.month', '<=', $this->to_date)
            ->when(!empty($this->customer_id), function ($query) {
                $query->where('ams_customers.id', $this->customer_id);
            })
            ->when(!empty($this->status), function ($query) {
                $query->where('ams_customers.status', $this->status);
            })
            ->select(
                'ams_customers.id',
                'ams_customers.name',
                'acp.voucher_no',
                'acp.month'
            )
            ->get()
            ->groupBy(['id'])
            ->toArray();

    }

    private function getMonthsBetween($from_date, $to_date) {
        $months = [];
        $start = new \DateTime($from_date . '-01');
        $end = new \DateTime($to_date . '-01');

        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->modify('+1 month');
        }

        return $months;
    }

    public function print($voucher_no, $print = null)
    {
        if (!empty($print) && !empty($voucher_no)) {
            $this->dispatchBrowserEvent('print-voucher', ['voucher_no' => $voucher_no, 'print' => 'true']);
        }
    }

    public function render()
    {
        return view('ams::livewire.reports.customer-payment-report');
    }
}