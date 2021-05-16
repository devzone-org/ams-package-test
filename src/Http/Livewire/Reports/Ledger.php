<?php


namespace Devzone\Ams\Http\Livewire\Reports;


use Devzone\Ams\Models\ChartOfAccount;
use Livewire\Component;

class Ledger extends Component
{
    public $search_accounts_modal = false;
    public $search_accounts;
    public $accounts = [];
    public $account_details = [];
    public $account_name;
    public $account_id;
    public $from_date;
    public $to_date;

    public function mount()
    {
        $this->from_date = date('Y-m-d', strtotime('-15 days'));
        $this->to_date = date('Y-m-d');
    }

    public function searchAccounts()
    {
        $this->search_accounts_modal = true;
        $this->emit('focusInput');
    }

    public function chooseAccount($id, $name)
    {
        $this->account_id = $id;
        $this->account_name = $name;
        $this->search_accounts_modal = false;
        $this->account_details = collect($this->accounts)->firstWhere('id', $id);
        $this->accounts = [];
        $this->search_accounts = '';

    }

    public function updatedSearchAccounts($value)
    {
        if (strlen($value) > 1) {
            $accounts = ChartOfAccount::where(function ($q) use ($value) {
                return $q->orWhere('name', 'LIKE', '%' . $value . '%')
                    ->orWhere('code', 'LIKE', '%' . $value . '%')
                    ->orWhere('type', 'LIKE', '%' . $value . '%');
            })->where('level', '5')->where('status', 't')
                ->get();
            if ($accounts->isNotEmpty()) {
                $this->accounts = $accounts->toArray();
            } else {
                $this->accounts = [];
            }
        } else {
            $this->accounts = [];
        }
    }

    public function render()
    {
        $ledger = [];
        if (!empty($this->account_id) && !empty($this->from_date) && !empty($this->to_date)) {
            $ledger = \Devzone\Ams\Models\Ledger::where('is_approve', 't')
                ->where('posting_date', '>=', $this->from_date)
                ->where('posting_date', '<=', $this->to_date)
                ->where('account_id',$this->account_id)
                ->select('voucher_no', 'posting_date', 'description', 'debit', 'credit')
                ->orderBy('voucher_no')->orderBy('posting_date')
                ->get()->toArray();
        }
        return view('ams::livewire.reports.ledger', compact('ledger'));
    }
}
