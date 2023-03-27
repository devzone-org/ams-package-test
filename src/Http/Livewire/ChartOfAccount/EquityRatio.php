<?php


namespace Devzone\Ams\Http\Livewire\ChartOfAccount;

use Livewire\Component;

class EquityRatio extends Component
{
    public $equity_data;

    public function mount()
    {
        $this->equityData();
    }

    public function equityData()
    {
        try {
            $this->equity_data = \Devzone\Ams\Models\EquityRatio::from('equity_ratio as er')
                ->leftjoin('chart_of_accounts as coa1', 'coa1.id', 'er.account_id')
                ->leftjoin('chart_of_accounts as coa2', 'coa2.id', 'er.drawing_account_id')
                ->select('er.partner_name', 'er.ratio', 'coa1.name as account_name', 'coa2.name as drawing_account_name')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }

    }
    public function render()
    {
        return view('ams::livewire.chart-of-accounts.equity-ratio');
    }
}
