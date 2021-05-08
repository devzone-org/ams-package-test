<?php


namespace Devzone\Ams\Http\Livewire\ChartOfAccount;


use Devzone\Ams\Helper\GeneralJournal;
use Devzone\Ams\Helper\Voucher;
use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Add extends Component
{
    public $is_contra = false;
    public $at_level;
    public $account_type;
    public $parent_account;
    public $account_name;
    public $opening_balance = 0;
    public $date;
    public $sub_accounts = [];
    public $show_opening_balance = false;
    public $success;

    public function mount()
    {
        $this->date = date('Y-m-d');
    }

    public function updated($name, $value)
    {
        if (!empty($this->at_level) && !empty($this->account_type)) {
            $this->sub_accounts = ChartOfAccount::where('level', $this->at_level)->where('type', $this->account_type)->get()->toArray();
        }
        if ($this->at_level == '4') {
            $this->show_opening_balance = true;
        } else {
            $this->reset(['show_opening_balance', 'opening_balance']);
        }
    }

    public function render()
    {
        return view('ams::livewire.chart-of-accounts.add');
    }

    public function create()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $code = null;
            if($this->at_level==4){
                $code = Voucher::instance()->coa()->get();
                $code = str_pad($code,7,"0",STR_PAD_LEFT);
            }
            $account_id = ChartOfAccount::create([
                'name' => $this->account_name,
                'type' => $this->account_type,
                'sub_account' => $this->parent_account,
                'level' => $this->at_level + 1,
                'code' => $code,
                'nature' => $this->determineNature(),
                'is_contra' => !empty($this->is_contra)?'t':'f',
                'status' => 't'
            ])->id;

            if($this->at_level == 4 && $this->opening_balance > 0){
                $voucher_no = Voucher::instance()->voucher()->get();
                $entry = GeneralJournal::instance()->account($account_id);
                if($this->determineNature()=='d'){
                    if(empty($this->is_contra)){
                        $entry = $entry->debit($this->opening_balance);
                    } else {
                        $entry = $entry->credit($this->opening_balance);
                    }
                } else {
                    if(empty($this->is_contra)){
                        $entry = $entry->credit($this->opening_balance);
                    } else {
                        $entry = $entry->debit($this->opening_balance);
                    }
                }
                $entry->description('Opening balance')->voucherNo($voucher_no)
                    ->date($this->date)->approve()->execute();
            }
            DB::commit();
            $this->success ='Account has been created.';
            $this->reset(['account_name','date','account_type','parent_account','at_level','is_contra','opening_balance','show_opening_balance','sub_accounts']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('account_name', $e->getMessage());
        }
    }

    protected function rules()
    {
        return [
            'at_level' => 'required|in:3,4',
            'account_type' => 'required|in:Assets,Liabilities,Equity,Income,Expenses',
            'parent_account' => 'required|integer',
            'account_name' => 'required|string|unique:chart_of_accounts,name',
            'date' => 'required|date|date_format:Y-m-d',
            'opening_balance' => 'numeric|required_if:at_level,4'
        ];
    }

    private function determineNature()
    {
        if (in_array($this->account_type, ['Assets', 'Expenses'])) {
            return 'd';
        } else {
            return 'c';
        }
    }
}
