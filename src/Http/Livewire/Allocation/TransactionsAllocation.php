<?php


namespace Devzone\Ams\Http\Livewire\Allocation;

use Devzone\Ams\Http\Traits\Searchable;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\Ledger;
use Devzone\Ams\Models\LedgerSettlement;
use DB;
use Livewire\Component;

class TransactionsAllocation extends Component
{
    use Searchable;
    public $from_date;
    public $to_date;
    public $account_name;
    public $unsettled_credit;
    public $unsettled_debit;
    public $first_check, $first_voucher, $unselect_all_debit, $unselect_all_credit;
    public $debit_checkbox = [], $credit_checkbox = [], $selected_debit_amount = 0, $selected_credit_amount = 0;
    public $success;

    protected $rules = [
        'account_name' => 'required',
        'from_date' => 'nullable|date',
        'to_date' => 'nullable|date'
    ];
    protected $validationAttributes = [
        'account_name' => 'Account Name',
        'from_date' => 'From Date',
        'to_date' => 'To Date',
        'debit_checkbox' => 'Debit Amount',
        'credit_checkbox' => 'Credit Amount',
    ];

    public function mount()
    {
        $this->from_date = date('d M Y', strtotime('-1 month'));
        $this->to_date = date('d M Y');
    }

    public function updated($key, $val)
    {
        try {
            $value = explode('.', $key);

            if ($value['0'] == 'debit_checkbox') {
                if (empty($this->credit_checkbox) && count($this->debit_checkbox) == 1) {
                    if ($val == true) {
                        $this->first_check = 'debit';
                        $this->first_voucher = array_keys($this->debit_checkbox)[0];
                    } else {
                        $this->first_check = '';
                        $this->first_voucher = '';
                    }
                }
                if ($val == false) {
                    unset($this->debit_checkbox[$value[1]]);
                }
                if ($this->selected_credit_amount <= $this->selected_debit_amount && !empty($this->credit_checkbox) && !empty($this->debit_checkbox) && $val != false) {
                    unset($this->debit_checkbox[$value[1]]);
                    $this->selected_debit_amount = collect($this->unsettled_debit)->whereIn('voucher_no', array_keys($this->debit_checkbox))->sum('unallocated');
                    throw new \Exception('Please select more Credit Transactions to select more Debit Transactions!!!');
                }
                if (count($this->debit_checkbox) > 1) {
                    $this->unselect_all_debit = true;
                } else {
                    $this->unselect_all_debit = false;
                }
                $this->selected_debit_amount = collect($this->unsettled_debit)->whereIn('voucher_no', array_keys($this->debit_checkbox))->sum('unallocated');
            }

            if ($value['0'] == 'credit_checkbox') {
                if (empty($this->debit_checkbox) && count($this->credit_checkbox) == 1) {
                    if ($val == true) {
                        $this->first_check = 'credit';
                        $this->first_voucher = array_keys($this->credit_checkbox)[0];
                    } else {
                        $this->first_check = '';
                        $this->first_voucher = '';
                    }
                }
                if ($val == false) {
                    unset($this->credit_checkbox[$value[1]]);
                }

                if ($this->selected_credit_amount >= $this->selected_debit_amount && !empty($this->credit_checkbox) && !empty($this->debit_checkbox) && $val != false) {
                    unset($this->credit_checkbox[$value[1]]);
                    $this->selected_credit_amount = collect($this->unsettled_credit)->whereIn('voucher_no', array_keys($this->credit_checkbox))->sum('unallocated');
                    throw new \Exception('Please select more Debit Transactions to select more Credit Transactions!!!');
                }
                if (count($this->credit_checkbox) > 1) {
                    $this->unselect_all_credit = true;
                } else {
                    $this->unselect_all_credit = false;
                }

                $this->selected_credit_amount = collect($this->unsettled_credit)->whereIn('voucher_no', array_keys($this->credit_checkbox))->sum('unallocated');
            }

            if ($key == 'unselect_all_credit') {
                $this->credit_checkbox = [];
                $this->selected_credit_amount = 0;
            }

            if ($key == 'unselect_all_debit') {
                $this->debit_checkbox = [];
                $this->selected_debit_amount = 0;
            }
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }
    }

    public function fetch()
    {

        $this->resetErrorBag();
        $this->success = empty($this->debit_checkbox) ? '' : $this->success;
        $this->validate();
        $this->reset(
            'selected_credit_amount',
            'selected_debit_amount',
            'credit_checkbox',
            'debit_checkbox',
            'unselect_all_credit',
            'unselect_all_debit',
            'first_voucher',
            'first_check'
        );
        $this->unsettled_credit = [];
        $this->unsettled_debit = [];

        try {
            if (auth()->user()->cannot('2.transactions-manual-allocation')) {
                throw new \Exception('You dont have the permission to do that!!!');
            }

            $account = ChartOfAccount::where('name', $this->account_name)->select('id')->first();

            if (empty($account)) {
                throw new \Exception('Account not found!!!');
            }

            if (empty($account['id'])) {
                throw new \Exception('Account id not found!!!');
            }

            $account_id = $account['id'];

            $entries = Ledger::where('account_id', $account_id)
                ->select('id', 'debit')
                ->get()
                ->toArray();

            if (!empty($entries)) {
                foreach ($entries as $e) {
                    if ($e['debit'] > 0) {
                        LedgerSettlement::updateOrCreate([
                            'ledger_id' => $e['id'],
                        ], [
                            'ledger_id' => $e['id'],
                        ]);
                    }
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
                    'ls.id as ledger_settlement_id',
                    'ls.amount',
                    'ls.status',
                    'ls.ledger_id'
                )
                ->orderBy('posting_date', 'asc')
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
                    'ls.id as ledger_settlement_id',
                    'ls.amount',
                    'ls.status',
                    'ls.ledger_id'
                )
                ->orderBy('posting_date', 'asc')
                ->get()
                ->groupBy('ledger_id')
                ->toArray();

            foreach ($unsettled_credit as $uc) {
                $total =  $uc[0]['credit'];
                $paid = collect($uc)->sum('amount');
                $remaining = $total - $paid;
                if ($remaining > 0) {
                    $this->unsettled_credit[] = [
                        'ledger_settlement_id' => $uc[0]['ledger_settlement_id'],
                        'ledger_id' => $uc[0]['ledger_id'],
                        'status' => $uc[0]['status'],
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
                    'ledger_settlement_id' => $ud[0]['ledger_settlement_id'],
                    'ledger_id' => $ud[0]['ledger_id'],
                    'status' => $ud[0]['status'],
                    'posting_date' => $ud[0]['posting_date'],
                    'voucher_no' => $ud[0]['voucher_no'],
                    'reference' => $ud[0]['reference'],
                    'debit' => $ud[0]['debit'],
                    'settled_amount' => collect($ud)->sum('amount'),
                    'unallocated' => $ud[0]['debit'] - collect($ud)->sum('amount'),
                ];
            }

            // dd($this->unsettled_credit, $this->unsettled_debit);
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }
    }

    public function allocate()
    {
        $this->resetErrorBag();
        $this->success = '';
        $this->validate(
            [
                'account_name' => 'required',
                'debit_checkbox' => 'required',
                'credit_checkbox' => 'required',
            ]
        );
        $lock = \Cache::lock('Customer' . $this->account_name, 60);
        try {

            if ($lock->get()) {
                DB::beginTransaction();

                if (auth()->user()->cannot('2.transactions-manual-allocation')) {
                    throw new \Exception('You dont have the permission to do that!!!');
                }

                $credit_settle = collect($this->unsettled_credit)->whereIn('voucher_no', array_keys($this->credit_checkbox))->toArray();
                $debit_settle = collect($this->unsettled_debit)->whereIn('voucher_no', array_keys($this->debit_checkbox))->toArray();

                foreach ($debit_settle as $d_key => $ds) {
                    foreach ($credit_settle as $c_key => $cs) {
                        if ($credit_settle[$c_key]['unallocated'] > 0 && $debit_settle[$d_key]['unallocated'] > 0) {
                            if ($debit_settle[$d_key]['unallocated'] > $credit_settle[$c_key]['unallocated']) {
                                $d_found = LedgerSettlement::find($ds['ledger_settlement_id']);
                                if ($d_found) {
                                    if ($d_found['amount'] == 0) {
                                        $d_found->update([
                                            'amount' => $credit_settle[$c_key]['unallocated'],
                                            'voucher_no' => $cs['voucher_no'],
                                        ]);
                                    } elseif ($d_found['amount'] > 0 && $d_found['status'] == 'f') {
                                        LedgerSettlement::create([
                                            'ledger_id' => $d_found['ledger_id'],
                                            'amount' => $credit_settle[$c_key]['unallocated'],
                                            'voucher_no' => $cs['voucher_no'],
                                        ]);
                                    }

                                    $debit_settle[$d_key]['unallocated'] = $debit_settle[$d_key]['unallocated'] -  $credit_settle[$c_key]['unallocated'];
                                    $credit_settle[$c_key]['unallocated'] = 0;
                                }
                            } elseif ($debit_settle[$d_key]['unallocated'] < $credit_settle[$c_key]['unallocated']) {
                                $d_found = LedgerSettlement::find($ds['ledger_settlement_id']);
                                if ($d_found) {
                                    if ($d_found['amount'] == 0) {
                                        $d_found->update([
                                            'amount' => $debit_settle[$d_key]['unallocated'],
                                            'voucher_no' => $cs['voucher_no'],
                                            'status' => 't',
                                        ]);
                                    } elseif ($d_found['amount'] > 0 && $d_found['status'] == 'f') {
                                        LedgerSettlement::create([
                                            'ledger_id' => $d_found['ledger_id'],
                                            'amount' => $debit_settle[$d_key]['unallocated'],
                                            'voucher_no' => $cs['voucher_no'],
                                            'status' => 't',
                                        ]);
                                    }

                                    LedgerSettlement::where('ledger_id', $d_found['ledger_id'])
                                        ->where('status', 'f')
                                        ->update([
                                            'status' => 't'
                                        ]);

                                    $debit_settle[$d_key]['unallocated'] = 0;
                                    $credit_settle[$c_key]['unallocated'] = $credit_settle[$c_key]['unallocated'] - $debit_settle[$d_key]['unallocated'];
                                }
                            } else {
                                $d_found = LedgerSettlement::find($ds['ledger_settlement_id']);
                                if ($d_found) {
                                    if ($d_found['amount'] == 0) {
                                        $d_found->update([
                                            'amount' => $debit_settle[$d_key]['unallocated'],
                                            'voucher_no' => $cs['voucher_no'],
                                            'status' => 't',
                                        ]);
                                    } elseif ($d_found['amount'] > 0 && $d_found['status'] == 'f') {
                                        LedgerSettlement::create([
                                            'ledger_id' => $d_found['ledger_id'],
                                            'amount' => $debit_settle[$d_key]['unallocated'],
                                            'voucher_no' => $cs['voucher_no'],
                                            'status' => 't',
                                        ]);
                                    }

                                    LedgerSettlement::where('ledger_id', $d_found['ledger_id'])
                                        ->where('status', 'f')
                                        ->update([
                                            'status' => 't'
                                        ]);

                                    $debit_settle[$d_key]['unallocated'] = 0;
                                    $credit_settle[$c_key]['unallocated'] = 0;
                                }
                            }
                        }
                    }
                }

                DB::commit();
                $this->success = "Allocation Completed!!!";
                $this->fetch();
                optional($lock)->release();
            }
        } catch (\Exception $e) {
            DB::rollback();
            optional($lock)->release();
            $this->addError('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('ams::livewire.allocation.transactions-allocation');
    }
}
