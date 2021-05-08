<?php


namespace Devzone\Ams\Http\Livewire\ChartOfAccount;


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


            DB::commit();
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

    private function determineNature($type)
    {
        if (in_array($type, ['Assets', 'Expenses'])) {
            return 'd';
        } else {
            return 'c';
        }
    }
}
