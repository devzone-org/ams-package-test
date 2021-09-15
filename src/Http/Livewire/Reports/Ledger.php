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

            if(!empty(request()->query('date'))){
                $this->from_date = date('d M Y',strtotime(request()->query('date')));
                $this->to_date = date('d M Y',strtotime(request()->query('date')));
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

        if (!empty($this->account_id) && !empty($this->from_date) && !empty($this->to_date)) {
            $this->ledger = \Devzone\Ams\Models\Ledger::where('is_approve', 't')
                ->where('posting_date', '>=', $this->formatDate($this->from_date))
                ->where('posting_date', '<=', $this->formatDate($this->to_date))
                ->where('account_id', $this->account_id)
                ->select('voucher_no', 'posting_date', 'description', 'debit', 'credit', 'account_id')
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
