<?php


namespace Devzone\Ams\Http\Livewire\Journal;


use Devzone\Ams\Helper\Voucher;
use Livewire\Component;

class Add extends Component
{
    public $posting_date;
    public $voucher_no;

    public function mount(){
        $this->posting_date = date('Y-m-d');
        $this->voucher_no = Voucher::instance()->tempVoucherOnly();
    }
    public function render()
    {
        return view('ams::livewire.journal.add');
    }
}
