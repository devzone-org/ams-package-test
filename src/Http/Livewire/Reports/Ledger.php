<?php


namespace Devzone\Ams\Http\Livewire\Reports;


use Devzone\Ams\Http\Traits\Searchable;
use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Ledger extends Component
{

    use Searchable;

    public $search_accounts_modal = false;
    public $search_accounts;
    public $accounts = [];
    public $account_details = [];
    public $account_name;
    public $account_name_s;
    public $account_id;
    public $from_date;
    public $to_date;
    public $from_d;
    public $to_d;
    public $ledger = [];
    public $opening_balance = 0;
    public $error;

    protected $listeners = ['emitAccountId'];

    public function mount($account_id)
    {
        $this->from_date = date('d M Y', strtotime('-1 month'));
        $this->to_date = date('d M Y');
        if ($account_id > 0) {

            $this->account_details = ChartOfAccount::find($account_id);
            $this->account_id = $account_id;
            $this->account_name = $this->account_details['name'];
            $this->dispatchBrowserEvent('title', ['name' => $this->account_name]);

            if (!empty(request()->query('date'))) {
                $this->from_date = date('d M Y', strtotime(request()->query('date')));
                $this->to_date = date('d M Y', strtotime(request()->query('date')));
                $this->from_d = $this->from_date;
                $this->to_d = $this->to_date;
            }
            if (!empty(request()->query('from')) && !empty(request()->query('to'))) {
                $this->from_date = date('d M Y', strtotime(request()->query('from')));
                $this->to_date = date('d M Y', strtotime(request()->query('to')));
                $this->from_d = $this->from_date;
                $this->to_d = $this->to_date;
            }
            $this->search();
        }

    }

    private function formatDate($date)
    {
        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');

    }

    public function search()
    {
        $this->account_name_s = $this->account_name;
        $this->from_d = $this->from_date;
        $this->to_d = $this->to_date;

        $this->reset(['ledger','error']);
        if (auth()->user()->can('2.hide-data-beyond-3-months') && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true) {
            $date = Carbon::parse($this->formatDate($this->from_date));
            $now = Carbon::now();
            $diff = $date->diffInDays($now);
            if (abs($diff) > 90) {
                $this->error = 'Records older than 90 days are not accessible.';
                return;
            }
        }
        if (auth()->user()->can('2.hide-assets') && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true) {
            $account = ChartOfAccount::find($this->account_id);
            if ($account['type'] == 'Assets') {
                $this->error = 'This ledger is not accessible.';
                return;
            }
        }
        if (auth()->user()->can('2.hide-liabilities') && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true) {
            $account = ChartOfAccount::find($this->account_id);
            if ($account['type'] == 'Liabilities') {
                $this->error = 'This ledger is not accessible.';
                return;
            }
        }
        if (auth()->user()->can('2.hide-equity') && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true) {
            $account = ChartOfAccount::find($this->account_id);
            if ($account['type'] == 'Equity') {
                $this->error = 'This ledger is not accessible.';
                return;
            }
        }
        if (auth()->user()->can('2.hide-income') && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true) {
            $account = ChartOfAccount::find($this->account_id);
            if ($account['type'] == 'Income') {
                $this->error = 'This ledger is not accessible.';
                return;
            }
        }
        if (auth()->user()->can('2.hide-expenses') && env('SKIP_ACCOUNTANT_RESTRICTION', false) !== true) {
            $account = ChartOfAccount::find($this->account_id);
            if ($account['type'] == 'Expenses') {
                $this->error = 'This ledger is not accessible.';
                return;
            }
        }


        if (!empty($this->account_id) && !empty($this->from_date) && !empty($this->to_date)) {
            $this->ledger = \Devzone\Ams\Models\Ledger::where('is_approve', 't')
                ->where('posting_date', '>=', $this->formatDate($this->from_date))
                ->where('posting_date', '<=', $this->formatDate($this->to_date))
                ->where('account_id', $this->account_id)
                ->select('voucher_no', 'reference', 'posting_date', 'description', 'debit', 'credit', 'account_id')
                ->orderBy('posting_date')->orderBy('voucher_no')
                ->get()->toArray();
            $opening = \Devzone\Ams\Models\Ledger::where('is_approve', 't')
                ->where('posting_date', '<', $this->formatDate($this->from_date))
                ->where('account_id', $this->account_id)
                ->select(DB::raw('sum(debit) as debit'), DB::raw('sum(credit) as credit'))
                ->first();
            if ($this->account_details['nature'] == 'd') {
                if ($this->account_details['is_contra'] == 'f') {
                    $this->opening_balance = $opening['debit'] - $opening['credit'];
                } else {
                    $this->opening_balance = $opening['credit'] - $opening['debit'];
                }

            } else {
                if ($this->account_details['is_contra'] == 'f') {
                    $this->opening_balance = $opening['credit'] - $opening['debit'];
                } else {
                    $this->opening_balance = $opening['debit'] - $opening['credit'];
                }
            }
        }
    }

    public function chooseAccount($id, $name)
    {
        $this->account_id = $id;
        $this->account_name = $name;
        $this->search_accounts_modal = false;
        $this->account_details = collect($this->accounts)->firstWhere('id', $id);
        $this->accounts = [];
        $this->search_accounts = '';
        $this->dispatchBrowserEvent('title', ['name' => $this->account_name]);
    }

    public function emitAccountId()
    {
        $this->account_details = ChartOfAccount::find($this->account_id);
        $this->dispatchBrowserEvent('title', ['name' => $this->account_name]);
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

    public function resetSearch()
    {
        $this->reset(['ledger', 'opening_balance', 'account_id', 'account_name', 'account_name_s', 'from_d', 'to_d']);
    }

    public function render()
    {
        return view('ams::livewire.reports.ledger');
    }
}
