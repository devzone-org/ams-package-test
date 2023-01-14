<?php

namespace Devzone\Ams\Http\Livewire\PettyExpenses;

use Devzone\Ams\Helper\GeneralJournal;
use Devzone\Ams\Helper\Voucher;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\PettyExpenses;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ClaimedPettyExpensesList extends Component
{
    public $type;
    public $filter = [];
    public $petty_expenses_list = [];
    public $fetch_account_heads = [];
    public $success;
    public $checked_petty_expenses = [];
    public $checked_all;
    public $approve_modal = false;
    public $approve_modal_msg;


    public function mount()
    {
        $this->fetch_account_heads = ChartOfAccount::where('type', 'Expenses')->where('level', 5)->where('status', 't')->select('id', 'name')->get()->toArray();
        $this->search();
    }

    public function updatedCheckedPettyExpenses()
    {
        if (count(array_filter($this->checked_petty_expenses)) == count($this->petty_expenses_list)) {
            $this->checked_all = true;
        } else {
            $this->checked_all = false;
        }
    }

    public function updatedCheckedAll($checked)
    {
        if ($checked) {
            $this->checked_petty_expenses = array_fill_keys(array_column($this->petty_expenses_list, 'id'), true);
        } else {
            $this->checked_petty_expenses = [];
        }
    }


    public function search()
    {
        $this->petty_expenses_list = PettyExpenses::join('chart_of_accounts as coa', 'coa.id', 'petty_expenses.account_head_id')
            ->leftJoin('users as u', 'u.id', 'petty_expenses.claimed_by')
            ->whereNotNull('petty_expenses.claimed_by')->whereNull('petty_expenses.approved_by')
            ->when(!empty($this->filter['invoice_date']), function ($q) {
                return $q->where('petty_expenses.invoice_date', $this->filter['invoice_date']);
            })
            ->when(!empty($this->filter['name']), function ($q) {
                return $q->where('petty_expenses.vendor_name', 'Like', '%' . $this->filter['name'] . '%');
            })
            ->when(!empty($this->filter['contact_no']), function ($q) {
                return $q->where('petty_expenses.vendor_contact_no', 'Like', '%' . $this->filter['contact_no'] . '%');
            })
            ->when(!empty($this->filter['account_head_id']), function ($q) {
                return $q->where('petty_expenses.account_head_id', $this->filter['account_head_id']);
            })
            ->select('petty_expenses.*', 'coa.name as account_head', 'u.name as claimed_by')
            ->orderBy('petty_expenses.invoice_date', 'asc')
            ->get()->toArray();

        $this->checked_all = false;
        $this->checked_petty_expenses = [];
    }

    public function openApproveModal()
    {
        $this->success = null;
        $this->resetErrorBag();
        $this->approve_modal_msg = "Are You Sure You wanted to Approve? This can't be undone";
        $this->approve_modal = true;
    }

    public function closeApproveModal()
    {
        $this->approve_modal_msg = null;
        $this->approve_modal = false;
    }

    public function reject()
    {
        try {
            if (!Auth::user()->can('3.reject.petty-expenses')) {
                throw new \Exception(env('PERMISSION_ERROR'));
            }
            DB::beginTransaction();
            if (empty(array_filter($this->checked_petty_expenses))) {
                throw new \Exception('Please select any record to proceed.');
            }

            foreach (array_keys(array_filter($this->checked_petty_expenses)) as $id) {
                $found = PettyExpenses::find($id);
                if (empty($found['claimed_by'])) {
                    $this->search();
                    throw new \Exception('Petty expenses has already been rejected. Please select again.');
                }

                $found->update([
                    'claimed_by' => null,
                    'claimed_at' => null
                ]);
            }

            $this->success = 'Petty Expenses Rejected Successfully.';
            $this->search();


            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->addError('error', $ex->getMessage());
        }
    }

    public function approve()
    {
        try {
            if (!Auth::user()->can('3.approve.petty-expenses')) {
                throw new \Exception(env('PERMISSION_ERROR'));
            }
            DB::beginTransaction();
            if (empty(array_filter($this->checked_petty_expenses))) {
                throw new \Exception('Please select any record to proceed.');
            }

            foreach (array_keys(array_filter($this->checked_petty_expenses)) as $id) {
                $found = PettyExpenses::find($id);
                if (!empty($found['approved_by'])) {
                    $this->search();
                    throw new \Exception('Petty expenses has already been approved. Please select again.');
                }
                $vno = Voucher::instance()->voucher()->get();

                $account_id = PettyExpenses::find($id)->paid_by_account_id;
                if (empty($account_id)) {
                    throw new \Exception('account not found.');
                }

                $exists = ChartOfAccount::where('id', $account_id)->exists();
                if (!$exists) {
                    throw new \Exception('account not found.');
                }

                $petty_pay = collect($this->petty_expenses_list)->where('id', $id)->first();
                $account_head_id = $petty_pay['account_head_id'];
                $amount = $petty_pay['amount'];
                $date = Carbon::now()->toDateString();

                $expense_head = ChartOfAccount::find($account_head_id);
                if (empty($expense_head)) {
                    throw new \Exception('Expense Head Account Not Found.');
                }

                $desc = 'Petty Payment of Amount PKR ' . $amount . '/- approved by ' . auth()->user()->name . ' at ' . date('d M, Y h:ia', strtotime(Carbon::now()->toDateTimeString())) . '
                against Voucher# ' . $vno . ' To ' . $expense_head['name'] . '.';

                GeneralJournal::instance()->account($account_id)->credit($amount)->voucherNo($vno)
                    ->date($date)->approve()->reference('petty-expenses')->description($desc)->execute();
                GeneralJournal::instance()->account($account_head_id)->debit($amount)->voucherNo($vno)
                    ->date($date)->approve()->reference('petty-expenses')->description($desc)->execute();

                $found->update([
                    'approved_by' => auth()->id(),
                    'approved_at' => Carbon::now()->toDateTimeString(),
                    'voucher_no' => $vno,
                ]);
            }

            $this->success = 'Petty Expenses Approved Successfully.';
            $this->search();


            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->addError('error', $ex->getMessage());
        }
    }

    public function clear()
    {
        $this->reset('filter');
    }


    public function render()
    {
        return view('ams::livewire.petty-expenses.claimed-petty-expenses-list');
    }
}