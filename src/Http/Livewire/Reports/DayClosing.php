<?php


namespace Devzone\Ams\Http\Livewire\Reports;


use App\Models\User;
use Livewire\Component;

class DayClosing extends Component
{

    public $from_date;
    public $to_date;
    public $users = [];
    public $report = [];
    public $user_account_id;

    public function mount()
    {
        $this->from_date = date('Y-m-d', strtotime('-1 month'));
        $this->to_date = date('Y-m-d');
        $this->users = User::from('users as u')->join('chart_of_accounts as coa', 'coa.id', '=', 'u.account_id')
            ->select('u.*')->get()->toArray();
    }

    public function render()
    {
        return view('ams::livewire.reports.day-closing');
    }

    public function search()
    {
        $this->report = \Devzone\Ams\Models\DayClosing::from('day_closing as dc')
            ->join('users as u', 'u.account_id', '=', 'dc.account_id')
            ->join('users as t', 't.account_id', '=', 'dc.transfer_to')
            ->join('users as c', 'c.id', '=', 'dc.close_by')
            ->select('dc.*', 'u.name as user_id', 't.name as transfer_name', 'c.name as close_by')
            ->where('dc.account_id', $this->user_account_id)
            ->whereDate('dc.created_at', '>=', $this->from_date)
            ->whereDate('dc.created_at', '<=', $this->to_date)
            ->orderBy('dc.date','asc')
            ->get()->toArray();


    }

}
