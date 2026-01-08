<?php

namespace Devzone\Ams\Http\Livewire\GeneralVouchers;

use Devzone\Ams\Models\Ledger;
use Devzone\Ams\Models\LedgerSettlement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use Livewire\Component;

class ManualAllocation extends Component
{
    public $customers = [], $selected_customer, $voucher_no, $posting_date, $from_date, $to_date;
    public $unsettled_debit_entries = [], $unsettled_credit_entries = [], $customer_details = [], $closing_balance;
    public $debit_checkbox = [], $credit_checkbox = [], $selected_debit_amount = 0, $selected_credit_amount = 0;
    public $select_all_debit, $select_all_credit;
    public $settled_debit_entries = [], $settled_credit_entries = [];
    public $customer_account_id, $deallocate = true, $settled_data = [];
    public $deallocate_checkbox;
    public $deallocate_checkbox_all;
    public $voucher_no_for_credit;

    public function mount(Request $req)
    {
        if (config('ams.ledger_settlement_enabled') !== true) {
            abort(404);
        }

        $customerModel = config('ams.customer_model');
        $map = config('ams.customer_table_map');

        $query = $customerModel::query();

        if ($map['status_column'] && $map['active_status_value']) {
            $query->where($map['status_column'], $map['active_status_value']);
        }

        $selects = [
            'id',
            $map['account_id'] . ' as account_id',
            $map['name'] . ' as customer_name'
        ];

        if ($map['code']) {
            $selects[] = $map['code'] . ' as customer_code';
        } else {
            $selects[] = 'id as customer_code';
        }

        $this->customers = $query->select($selects)
            ->get()
            ->toArray();

        if (!empty($req->account_id)) {
            $this->selected_customer = $req->account_id;
            $this->SearchAmount();
        }
    }

    public function SearchAmount()
    {
        $this->reset('debit_checkbox', 'credit_checkbox', 'selected_debit_amount', 'selected_credit_amount', 'select_all_debit', 'select_all_credit');

        try {
            if ($this->deallocate == true) {
                $this->settled_data = [];
                if (empty($this->selected_customer)) {
                    throw new Exception('Customer field is required!!!');
                }

                $this->customer_account_id = $this->selected_customer;
                $this->settled_debit_entries = [];
                $this->settled_credit_entries = [];
                $this->unsettled_debit_entries = [];

                $this->unsettled_debit_entries = Ledger::leftJoinSub(function ($query) {
                    $query->select('ledger_id', 'status', 'id', 'voucher_no', 'account_id', DB::raw('SUM(amount) as amount'))
                        ->from('ledger_settlements as ls')
                        ->where('account_id', $this->selected_customer)
                        ->groupBy('ledger_id', 'account_id');
                }, 'ls', 'ledgers.id', '=', 'ls.ledger_id')
                    ->where('ledgers.account_id', $this->selected_customer)
                    ->where(function ($q) {
                        $q->where('ls.status', 'f')
                            ->orWhereNull('ls.status');
                    })
                    ->where('debit', '>', 0)
                    ->when(!empty($this->voucher_no), function ($q) {
                        $q->where('ledgers.voucher_no', $this->voucher_no);
                    })
                    ->when(!empty($this->from_date), function ($q) {
                        $q->where('ledgers.posting_date', '>=', $this->from_date);
                    })
                    ->when(!empty($this->to_date), function ($q) {
                        $q->where('ledgers.posting_date', '<=', $this->to_date);
                    })
                    ->select(
                        'ledgers.posting_date',
                        'ledgers.voucher_no',
                        'ledgers.reference as reference_no',
                        'ledgers.debit',
                        'ledgers.id',
                        DB::raw('COALESCE(ls.amount, 0) as settled_amount'),
                        DB::raw('ledgers.debit - COALESCE(ls.amount, 0) as unallocated'),

                    )
                    ->orderBy('ledgers.posting_date', 'asc')
                    ->orderBy('ledgers.voucher_no', 'asc')
                    ->get()->toArray();


                $this->unsettled_credit_entries = Ledger::leftJoinSub(function ($query) {
                    $query->select('account_id', 'voucher_no', DB::raw('SUM(amount) as amount'), 'cr_ledger_id')
                        ->from('ledger_settlements as ls')
                        ->where('account_id', $this->selected_customer)
                        ->groupBy('voucher_no', 'account_id', 'cr_ledger_id');
                }, 'ls', 'ledgers.id', '=', 'ls.cr_ledger_id')->where('ledgers.credit', '>', 0)
                    ->where('ledgers.account_id', $this->selected_customer)
                    ->where('ledgers.credit', '<>', DB::raw('COALESCE(ls.amount, 0)'))
                    ->when(!empty($this->voucher_no_for_credit), function ($q) {
                        $q->where('ledgers.voucher_no', $this->voucher_no_for_credit);
                    })
                    ->when(!empty($this->from_date), function ($q) {
                        $q->where('ledgers.posting_date', '>=', $this->from_date);
                    })
                    ->when(!empty($this->to_date), function ($q) {
                        $q->where('ledgers.posting_date', '<=', $this->to_date);
                    })
                    ->select(
                        'ls.amount',
                        'ls.cr_ledger_id',
                        'ledgers.posting_date',
                        'ledgers.voucher_no',
                        'ledgers.reference as reference_no',
                        'ledgers.credit',
                        'ledgers.id',
                        DB::raw('ledgers.credit - COALESCE(ls.amount, 0) as unallocated'),
                    )
                    ->orderBy('ledgers.posting_date', 'asc')
                    ->orderBy('ledgers.voucher_no', 'asc')
                    ->get()->toArray();

                $customerModel = config('ams.customer_model');
                $map = config('ams.customer_table_map');

                $selects = [];
                if ($map['payment_terms']) $selects[] = $map['payment_terms'] . ' as frequency';
                if ($map['credit_limit']) $selects[] = $map['credit_limit'] . ' as amount';
                if ($map['grace_period']) $selects[] = $map['grace_period'] . ' as grace_period';

                if (!empty($selects)) {
                    $this->customer_details = $customerModel::where($map['account_id'], $this->selected_customer)
                        ->select($selects)
                        ->get()->toArray();
                } else {
                    $this->customer_details = [];
                }

                $ledger = Ledger::where('account_id', $this->selected_customer)
                    ->select(
                        DB::raw('sum(debit) as debit'),
                        DB::raw('sum(credit) as credit'),
                    )
                    ->first();
                $this->closing_balance = $ledger['debit'] - $ledger['credit'];
            } else {
                if (empty($this->selected_customer)) {
                    throw new Exception('Customer field is required!!!');
                }

                $this->unsettled_debit_entries = [];
                $this->unsettled_credit_entries = [];
                $this->customer_details = [];

                $this->settled_debit_entries = [];

                $this->settled_debit_entries = Ledger::leftJoinSub(function ($query) {
                    $query->select('ledger_id', 'cr_ledger_id', 'status', 'id', 'voucher_no', 'account_id', DB::raw('SUM(amount) as amount'))
                        ->from('ledger_settlements as ls')
                        ->where('account_id', $this->selected_customer)
                        ->groupBy('ledger_id', 'account_id');
                }, 'ls', 'ledgers.id', '=', 'ls.ledger_id')
                    ->where('ledgers.account_id', $this->selected_customer)
                    ->when(!empty($this->voucher_no), function ($q) {
                        $q->where('ledgers.voucher_no', $this->voucher_no);
                    })
                    ->where('debit', '>', 0)
                    ->where('ls.amount', '>', 0)
                    ->when(!empty($this->from_date), function ($q) {
                        $q->where('ledgers.posting_date', '>=', $this->from_date);
                    })
                    ->when(!empty($this->to_date), function ($q) {
                        $q->where('ledgers.posting_date', '<=', $this->to_date);
                    })
                    ->select(
                        'ledgers.id as ledger_id',
                        'ledgers.account_id',
                        'ledgers.posting_date',
                        'ledgers.voucher_no',
                        'ledgers.reference as reference_no',
                        'ledgers.debit',
                        'ls.cr_ledger_id',
                        DB::raw('COALESCE(ls.amount, 0) as settled_amount'),
                        DB::raw('ledgers.debit - COALESCE(ls.amount, 0) as unallocated'),

                    )
                    ->orderBy('ledgers.posting_date', 'asc')
                    ->orderBy('ledgers.voucher_no', 'asc')
                    ->get();


                $ledger_ids = $this->settled_debit_entries->pluck('ledger_id');
                $this->settled_debit_entries = $this->settled_debit_entries->groupBy('ledger_id')->toArray();

                $this->settled_credit_entries = [];

                $this->settled_credit_entries = Ledger::leftJoinSub(function ($query) {
                    $query->select('ledger_id', 'cr_ledger_id', 'status', 'id', 'voucher_no', 'account_id', DB::raw('SUM(amount) as amount'))
                        ->from('ledger_settlements as ls')
                        ->where('account_id', $this->selected_customer)
                        ->groupBy('ledger_id', 'account_id', 'cr_ledger_id');
                }, 'ls', 'ledgers.id', '=', 'ls.cr_ledger_id')
                    ->where('ledgers.account_id', $this->selected_customer)
                    ->where('credit', '>', 0)
                    ->where(function ($q) use ($ledger_ids) {
                        $q->when(!empty($ledger_ids), function ($innerQ) use ($ledger_ids) {
                            $innerQ->whereIn('ls.ledger_id', $ledger_ids);
                        })
                            ->Where(function ($innerQ) {
                                $innerQ->when(!empty($this->voucher_no_for_credit), function ($subQ) {
                                    $subQ->where('ledgers.voucher_no', $this->voucher_no_for_credit);
                                });
                            });
                    })
                    ->when(!empty($this->from_date), function ($q) {
                        $q->where('ledgers.posting_date', '>=', $this->from_date);
                    })
                    ->when(!empty($this->to_date), function ($q) {
                        $q->where('ledgers.posting_date', '<=', $this->to_date);
                    })
                    ->select(
                        'ls.id as settled_ledger_id',
                        'ls.ledger_id as ledger_settlement_id',
                        'ledgers.id as ledger_id',
                        'ledgers.posting_date',
                        'ledgers.voucher_no',
                        'ledgers.reference as reference_no',
                        'ledgers.credit',
                        DB::raw('COALESCE(ls.amount, 0) as allocated'),
                    )
                    ->orderBy('ledgers.posting_date', 'asc')
                    ->orderBy('ledgers.voucher_no', 'asc')
                    ->get();

                $this->settled_credit_entries = $this->settled_credit_entries->groupBy('ledger_settlement_id')->toArray();
                $this->settled_data = [];
                foreach ($this->settled_debit_entries as $ledger_id => $data) {
                    $credit = !empty($this->settled_credit_entries[$ledger_id]) ? $this->settled_credit_entries[$ledger_id] : [];
                    $this->settled_data[$ledger_id]['debit'] = $this->settled_debit_entries[$ledger_id];
                    $this->settled_data[$ledger_id]['credit'] = $credit;
                }
                if (empty($this->settled_data)) {
                    $this->dispatchBrowserEvent('show-errors', ['bag' => ['No record found!!']]);
                }
            }
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('show-errors', ['bag' => [$e->getMessage()]]);
        }
    }

    public function updated($key, $val)
    {
        try {
            $value = explode('.', $key);
            if ($value['0'] == 'deallocate_checkbox') {
                if ($val == false) {
                    unset($this->deallocate_checkbox[$value[1]]);
                }
                if (count($this->settled_data) == count($this->deallocate_checkbox)) {
                    $this->deallocate_checkbox_all = true;
                } else {
                    $this->deallocate_checkbox_all = false;
                }
            }
            if ($value[0] == 'deallocate_checkbox_all') {
                if ($val == false) {
                    $this->deallocate_checkbox = [];
                } else {
                    foreach ($this->settled_data as $k => $v) {
                        $this->deallocate_checkbox[$k] = true;
                    }
                }
            }

            if ($value['0'] == 'debit_checkbox') {
                if ($val == false) {
                    unset($this->debit_checkbox[$value[1]]);
                }

                if (count($this->debit_checkbox) > 1) {
                    $this->select_all_debit = true;
                } else {
                    $this->select_all_debit = false;
                }
                $this->selected_debit_amount = Collect($this->unsettled_debit_entries)->whereIn('id', array_keys($this->debit_checkbox))->sum('unallocated');
            }

            if ($value['0'] == 'credit_checkbox') {
                if ($val == false) {
                    unset($this->credit_checkbox[$value[1]]);
                }

                if (count($this->credit_checkbox) > 1) {
                    $this->select_all_credit = true;
                } else {
                    $this->select_all_credit = false;
                }

                $this->selected_credit_amount = Collect($this->unsettled_credit_entries)->whereIn('id', array_keys($this->credit_checkbox))->sum('unallocated');
            }

            if ($key == 'select_all_credit') {
                if ($val == false) {
                    $this->credit_checkbox = [];
                    $this->selected_credit_amount = 0;
                } else {
                    foreach ($this->unsettled_credit_entries as $uce) {
                        $this->credit_checkbox[$uce['id']] = true;
                    }
                    $this->selected_credit_amount = Collect($this->unsettled_credit_entries)->sum('unallocated');
                }
            }

            if ($key == 'select_all_debit') {
                if ($val == false) {
                    $this->debit_checkbox = [];
                    $this->selected_debit_amount = 0;
                } else {
                    foreach ($this->unsettled_debit_entries as $ude) {
                        $this->debit_checkbox[$ude['id']] = true;
                    }
                    $this->selected_debit_amount = Collect($this->unsettled_debit_entries)->sum('unallocated');
                }
            }
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('show-errors', ['bag' => [$e->getMessage()]]);
        }
    }

    public function allocate()
    {
        $this->withValidator(function (Validator $validator) {
            if ($validator->fails()) {
                $this->dispatchBrowserEvent('show-errors', ['bag' => $validator->errors()->all()]);
            }
        })->validate(
            [
                'selected_customer' => 'required',
                'debit_checkbox' => 'required',
                'credit_checkbox' => 'required',
            ],
            [],
            [
                'selected_customer' => 'Customer',
                'debit_checkbox' => 'Debit Amount',
                'credit_checkbox' => 'Credit Amount',
            ]
        );
        $lock = Cache::lock('Customer' . $this->selected_customer, 60);
        try {
            if ($lock->get()) {
                DB::beginTransaction();
                $this->customerAllocation($this->selected_customer, array_keys($this->debit_checkbox), array_keys($this->credit_checkbox));
                DB::commit();
                $this->dispatchBrowserEvent('show-success', ['bag' => ["Allocation Completed!!!"]]);
                $this->SearchAmount();
                $this->reset('selected_credit_amount', 'selected_debit_amount', 'credit_checkbox', 'debit_checkbox', 'select_all_credit', 'select_all_debit');
                optional($lock)->release();
            }
        } catch (Exception $e) {
            DB::rollback();
            optional($lock)->release();
            $this->dispatchBrowserEvent('show-errors', ['bag' => [$e->getMessage()]]);
        }
    }

    public function deallocate()
    {
        try {
            DB::beginTransaction();
            $description = '';
            if (!empty($this->deallocate_checkbox)) {
                foreach (array_keys($this->deallocate_checkbox) as $ids) {
                    $found = LedgerSettlement::where('ledger_id', $ids);
                    $found->update([
                        'amount' => 0.00,
                        'status' => 'f',
                        'voucher_no' => null,
                        'cr_ledger_id' => null,
                    ]);
                    $f = Ledger::find($ids);
                }
            } else {
                throw new Exception('No entry is selected!!!');
            }

            DB::commit();
            $this->dispatchBrowserEvent('show-success', ['bag' => ["Deallocation Completed!!!"]]);
            $this->reset('settled_debit_entries', 'settled_credit_entries', 'customer_account_id', 'settled_data');
        } catch (Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('show-errors', ['bag' => [$e->getMessage()]]);
        }
    }

    public function render()
    {
        return view('ams::livewire.general-vouchers.manual-allocation')->extends('ams::layouts.master');
    }

    private function customerAllocation($account_id, $debit_voucher_nos = null, $credit_voucher_nos = null)
    {
        $debit_data_to_settle = Ledger::leftJoinSub(function ($query) use ($account_id) {
            $query->select('ledger_id', 'status', 'id as ls_id', 'voucher_no', 'account_id', DB::raw('SUM(amount) as amount'))
                ->from('ledger_settlements as ls')
                ->where('account_id', $account_id)
                ->groupBy('ledger_id', 'account_id');
        }, 'ls', 'ledgers.id', '=', 'ls.ledger_id')
            ->where('ledgers.account_id', $account_id)
            ->where(function ($q) {
                $q->where('ls.status', 'f')
                    ->orWhereNull('ls.status');
            })
            ->where('debit', '>', 0)
            ->when(!empty($debit_voucher_nos), function ($q) use ($debit_voucher_nos) {
                $q->whereIn('ledgers.id', $debit_voucher_nos);
            })
            ->select(
                'ledgers.id',
                'ledgers.voucher_no',
                'ls.ls_id as ledger_settlement_id',
                DB::raw('ledgers.debit - COALESCE(ls.amount, 0) as debit_amount'),
            )
            ->orderBy('ledgers.posting_date', 'asc')
            ->get()->toArray();

        $credit_data_to_settle = Ledger::leftJoinSub(function ($query) use ($account_id) {
            $query->select('account_id', 'cr_ledger_id', 'voucher_no', DB::raw('SUM(amount) as amount'))
                ->from('ledger_settlements as ls')
                ->where('account_id', $account_id)
                ->groupBy('voucher_no', 'account_id', 'cr_ledger_id');
        }, 'ls', 'ledgers.id', '=', 'ls.cr_ledger_id')->where('ledgers.credit', '>', 0)
            ->where('ledgers.account_id', $account_id)
            ->where('ledgers.credit', '<>', DB::raw('COALESCE(ls.amount, 0)'))
            ->when(!empty($credit_voucher_nos), function ($q) use ($credit_voucher_nos) {
                $q->whereIn('ledgers.id', $credit_voucher_nos);
            })
            ->select(
                'ledgers.voucher_no',
                DB::raw('ledgers.credit - COALESCE(ls.amount, 0) as amount'),
                'ledgers.posting_date',
                'ledgers.id'
            )
            ->orderBy('ledgers.posting_date', 'asc')
            ->get()->toArray();

        foreach ($debit_data_to_settle as $k => $ddts) {
            foreach ($credit_data_to_settle as $key => $cdts) {
                if (round($credit_data_to_settle[$key]['amount'], 2) > 0 && round($debit_data_to_settle[$k]['debit_amount'], 2) > 0) {
                    $debit_amount = round($debit_data_to_settle[$k]['debit_amount'], 2);
                    $credit_amount = round($credit_data_to_settle[$key]['amount'], 2);

                    if ($debit_amount > $credit_amount) {
                        $f = LedgerSettlement::find($ddts['ledger_settlement_id']);
                        if (!empty($f)) {
                            $updateData = [
                                'amount' => $credit_amount,
                                'voucher_no' => $cdts['voucher_no'],
                                'account_id' => $account_id,
                                'cr_ledger_id' => $credit_data_to_settle[$key]['id']
                            ];
                            if ($f['amount'] == 0) {
                                $f->update($updateData);
                            } else {
                                $updateData['ledger_id'] = $f['ledger_id'];
                                $updateData['location'] = '6';
                                LedgerSettlement::create($updateData);
                            }
                        } else {
                            LedgerSettlement::create([
                                'ledger_id' => $ddts['id'],
                                'amount' => $credit_amount,
                                'voucher_no' => $cdts['voucher_no'],
                                'account_id' => $account_id,
                                'cr_ledger_id' => $credit_data_to_settle[$key]['id'],
                                'location' => '6'
                            ]);
                        }
                        $debit_data_to_settle[$k]['debit_amount'] = $debit_amount - $credit_amount;
                        $credit_data_to_settle[$key]['amount'] = 0;
                    } elseif ($debit_amount < $credit_amount) {
                        $f = LedgerSettlement::find($ddts['ledger_settlement_id']);
                        if (!empty($f)) {
                            $updateData = [
                                'amount' => $debit_amount,
                                'voucher_no' => $cdts['voucher_no'],
                                'account_id' => $account_id,
                                'status' => 't',
                                'cr_ledger_id' => $credit_data_to_settle[$key]['id']
                            ];
                            if ($f['amount'] == 0) {
                                $f->update($updateData);
                            } else {
                                $updateData['ledger_id'] = $f['ledger_id'];
                                $updateData['location'] = '6';
                                LedgerSettlement::create($updateData);
                            }
                            LedgerSettlement::where('ledger_id', $f['ledger_id'])->where('status', 'f')->update([
                                'status' => 't'
                            ]);
                        } else {
                            LedgerSettlement::create([
                                'ledger_id' => $ddts['id'],
                                'amount' => $debit_amount,
                                'voucher_no' => $cdts['voucher_no'],
                                'account_id' => $account_id,
                                'status' => 't',
                                'cr_ledger_id' => $credit_data_to_settle[$key]['id'],
                                'location' => '6'
                            ]);
                        }
                        $credit_data_to_settle[$key]['amount'] = $credit_amount - $debit_amount;
                        $debit_data_to_settle[$k]['debit_amount'] = 0;
                    } else {
                        $f = LedgerSettlement::find($ddts['ledger_settlement_id']);
                        if (!empty($f)) {
                            $updateData = [
                                'amount' => $debit_amount,
                                'voucher_no' => $cdts['voucher_no'],
                                'account_id' => $account_id,
                                'status' => 't',
                                'cr_ledger_id' => $credit_data_to_settle[$key]['id']
                            ];
                            if ($f['amount'] == 0) {
                                $f->update($updateData);
                            } else {
                                $updateData['ledger_id'] = $f['ledger_id'];
                                $updateData['location'] = '6';
                                LedgerSettlement::create($updateData);
                            }
                            LedgerSettlement::where('ledger_id', $f['ledger_id'])->where('status', 'f')->update([
                                'status' => 't'
                            ]);
                        } else {
                            LedgerSettlement::create([
                                'ledger_id' => $ddts['id'],
                                'amount' => $debit_amount,
                                'voucher_no' => $cdts['voucher_no'],
                                'account_id' => $account_id,
                                'status' => 't',
                                'cr_ledger_id' => $credit_data_to_settle[$key]['id'],
                                'location' => '6'
                            ]);
                        }
                        $debit_data_to_settle[$k]['debit_amount'] = 0;
                        $credit_data_to_settle[$key]['amount'] = 0;
                    }
                }
            }
        }
    }
}
