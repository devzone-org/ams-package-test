<?php


namespace Devzone\Ams\Http\Livewire\Journal;


use Devzone\Ams\Helper\Voucher;
use Devzone\Ams\Models\ChartOfAccount;
use Livewire\Component;

class Add extends Component
{
    public $posting_date;
    public $voucher_no;
    public $entries = [];
    public $search_accounts_modal = false;
    public $search_accounts;
    public $accounts = [];
    public $key_id;

    public function mount()
    {
        $this->posting_date = date('Y-m-d');
        $this->voucher_no = Voucher::instance()->tempVoucherOnly();
        $this->entries[] = $this->defaultEntries();
    }

    private function defaultEntries()
    {
        return [
            'account_id' => '',
            'account_name' => '',
            'description' => '',
            'debit' => 0,
            'credit' => 0,
        ];
    }

    public function addEntry()
    {
        $this->entries[] = $this->defaultEntries();
    }

    public function removeEntry($key)
    {
        unset($this->entries[$key]);
    }

    public function searchAccounts($key)
    {
        $this->search_accounts_modal = true;
        $this->key_id = $key;
    }

    public function chooseAccount($id,$name){
        $this->entries[$this->key_id]['account_id'] = $id;
        $this->entries[$this->key_id]['account_name'] = $name;
        $this->search_accounts_modal = false;
        $this->accounts = [];
        $this->search_accounts = '';
        $this->entries[$this->key_id]['description'] = $this->entries[0]['description'];
    }

    public function updatedSearchAccounts($value)
    {
        if (strlen($value) > 1) {
            $accounts = ChartOfAccount::where('name', 'LIKE', '%' . $value . '%')->where('level', '5')->where('status', 't')->get();
            if ($accounts->isNotEmpty()) {
                $this->accounts = $accounts->toArray();
            } else {
                $this->accounts = [];
            }
        } else {
            $this->accounts = [];
        }
    }

    public function render()
    {
        return view('ams::livewire.journal.add');
    }
}
