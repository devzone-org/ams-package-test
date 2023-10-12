<?php


namespace Devzone\Ams\Http\Livewire\Allocation;

use Devzone\Ams\Http\Traits\Searchable;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\Ledger;
use Devzone\Ams\Models\LedgerSettlement;
use Livewire\Component;

class TransactionsAllocation extends Component
{
    use Searchable;
    public $from_date;
    public $to_date;
    public $account_name;
    public $unsettled_credit;
    public $unsettled_debit;
    public $first_check, $first_voucher, $select_all_debit, $select_all_credit;
    public $debit_checkbox = [], $credit_checkbox = [], $selected_debit_amount = 0, $selected_credit_amount = 0;

    protected $rules = [
        'account_name' => 'required',
        'from_date' => 'nullable|date',
        'to_date' => 'nullable|date'
    ];
    protected $validationAttributes = [
        'account_name' => 'Account Name',
        'from_date' => 'From Date',
        'to_date' => 'To Date'
    ];

    public function mount()
    {
        $this->from_date = date('d M Y', strtotime('-1 month'));
        $this->to_date = date('d M Y');
    }

    public function fetch()
    {
        $this->validate();
        $this->resetErrorBag();
        $this->unsettled_credit = [];
        $this->unsettled_debit = [];

        $account_id = ChartOfAccount::where('name', $this->account_name)->select('id')->first()->id;

        $entries = Ledger::where('account_id', $account_id)
        ->select('id')
        ->get()
        ->toArray();

        if (!empty($entries)) {
            foreach ($entries as $e) {
                LedgerSettlement::updateOrCreate([
                    'ledger_id' => $e['id'],
                ], [
                    'ledger_id' => $e['id'],
                ]);
            }
        }

        $unsettled_credit = Ledger::from('ledgers as l')
        ->leftjoin('ledger_settlements as ls', 'ls.voucher_no', 'l.voucher_no')
        ->where('l.account_id', $account_id)
        ->where('credit', '!=', '0.00')
        ->select(
            'l.id',
            'l.posting_date',
            'l.voucher_no',
            'l.reference',
            'l.debit',
            'l.credit',
            'ls.amount',
            'ls.status',
            'ls.ledger_id'
        )
        ->get()
        ->groupBy('voucher_no')
        ->toArray();

        $unsettled_debit = Ledger::from('ledgers as l')
        ->leftjoin('ledger_settlements as ls', 'ls.ledger_id', 'l.id')
        ->where('l.account_id', $account_id)
        ->where('ls.status', 'f')
        ->where('debit', '!=', '0.00')
        ->select(
            'l.id',
            'l.posting_date',
            'l.voucher_no',
            'l.reference',
            'l.debit',
            'l.credit',
            'ls.amount',
            'ls.status',
            'ls.ledger_id'
        )
        ->get()
        ->groupBy('ledger_id')
        ->toArray();

        foreach ($unsettled_credit as $uc) {
            $total =  $uc[0]['credit'];
            $paid = collect($uc)->sum('amount');
            $remaining = $total - $paid;
            if ($remaining > 0) {
                $this->unsettled_credit[] = [
                    'posting_date' => $uc[0]['posting_date'],
                    'voucher_no' => $uc[0]['voucher_no'],
                    'reference' => $uc[0]['reference'],
                    'credit' => $uc[0]['credit'],
                    'unallocated' => $remaining,
                ];
            }
        }

        foreach ($unsettled_debit as $ud) {
            $this->unsettled_debit[] = [
                'posting_date' => $ud[0]['posting_date'],
                'voucher_no' => $ud[0]['voucher_no'],
                'reference' => $ud[0]['reference'],
                'debit' => $ud[0]['debit'],
                'settled_amount' => collect($ud)->sum('amount'),
                'unallocated' => $ud[0]['debit'] - collect($ud)->sum('amount'),
            ];
        }

        // dd($this->unsettled_credit, $this->unsettled_debit);

    }

    public function render()
    {
        return view('ams::livewire.allocation.transactions-allocation');
    }
}
