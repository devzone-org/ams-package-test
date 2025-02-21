<?php


namespace Devzone\Ams\Http\Livewire\Journal;


use Devzone\Ams\Models\AmsCustomerPayment;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\Ledger;
use Devzone\Ams\Models\LedgerAttachment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $posting_date;
    public $voucher_no;
    public $entries = [];
    public $attachment_entries = [];
    public $search_accounts_modal = false;
    public $search_accounts;
    public $accounts = [];
    public $key_id;
    public $deleted = [];
    public $deleted_attachment = [];
    public $success;
    public $file_upload = false;
    public $account_list = [];
    public $highlightIndex = 0;

    public $customer_coa_level_4_id;
    public $customer_account_present = false;
    public $invoice_paid = 'f';
    public $selected_month;


    protected $rules = [
        'entries.*.account_id' => 'required|integer',
        'posting_date' => 'required|date|date_format:d M Y',
        'voucher_no' => 'required|integer',
        'entries.*.description' => 'required|string',
        'entries.*.debit' => 'nullable|numeric',
        'entries.*.credit' => 'nullable|numeric',
    ];

    protected $validationAttributes = [
        'entries.*.account_id' => 'account',
        'entries.*.debit' => 'debit',
        'entries.*.credit' => 'credit',
        'entries.*.description' => 'description',

    ];

    public function mount($voucher_no)
    {
        $this->voucher_no = $voucher_no;
//        $this->account_list = ChartOfAccount::where('level', 5)->orderBy('type')->get()->toArray();
//        $this->accounts = $this->account_list;
        $temp_entries = $this->getTempEntries();
        if(env('AMS_CUSTOMER', false) === true) {
            $customer_coa = ChartOfAccount::where('reference', 'ams-customers-l4')->select('id')->first();
            if (!empty($customer_coa['id'])) {
                $this->customer_coa_level_4_id = $customer_coa['id'];
            }
        }

        if ($temp_entries->isNotEmpty()) {
            $this->posting_date = date('d M Y', strtotime($temp_entries->first()->posting_date));
            $this->voucher_no = $temp_entries->max('voucher_no');
            $this->entries = $temp_entries->toArray();

            if(env('AMS_CUSTOMER', false) === true) {
                $coas_data = ChartOfAccount::whereIn('id', array_column($this->entries, 'account_id'))->pluck('sub_account')->toArray();
                if (in_array($this->customer_coa_level_4_id, $coas_data)) {
                    $this->customer_account_present = true;
                }

                $payment_entry_found = AmsCustomerPayment::where('voucher_no', $this->voucher_no)->where('temp_voucher', 't')->first();
                if (!empty($payment_entry_found)) {
                    $this->selected_month = $payment_entry_found['month'];
                    $this->invoice_paid = 't';
                    $this->customer_account_present = true;
                }
            }

            $attachments = LedgerAttachment::where('type', '0')->where('voucher_no', $this->voucher_no)->get();
            if ($attachments->isNotEmpty()) {
                $this->attachment_entries = $attachments->toArray();
            }
        } else {
            $this->reset(['attachment_entries', 'entries']);
            $this->addError('voucher_no', 'Invalid voucher no or this entry already posted.');
        }

    }

    private function getTempEntries()
    {
        return Ledger::from('ledgers as l')
            ->leftJoin('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
            ->where('l.voucher_no', $this->voucher_no)
            ->where('l.is_approve', 'f')
            ->select('l.*', DB::raw('CONCAT(  coa.name) as account_name'))->get();
    }

    public function addEntry()
    {
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

    public function addAttachmentEntry()
    {
        $this->attachment_entries[] = $this->defaultAttachmentEntries();
    }

    private function defaultAttachmentEntries()
    {
        return [
            'account_id' => '',
            'file' => '',
        ];
    }

    public function removeAttachmentEntry($key)
    {
        $entry = $this->attachment_entries[$key];
        unset($this->attachment_entries[$key]);
        if (isset($entry['id'])) {
            $this->deleted_attachment[] = $entry['id'];
        }
    }

    public function removeEntry($key)
    {
        $entry = $this->entries[$key];
        unset($this->entries[$key]);
        if (isset($entry['id'])) {
            $this->deleted[] = $entry['id'];
        }
        if(env('AMS_CUSTOMER', false) === true) {
            $this->checkForExistenceOfCustomerAccount();
        }
    }

    private function checkForExistenceOfCustomerAccount()
    {
        if(!empty(array_column($this->entries, 'account_id'))){
            $coas_data = ChartOfAccount::whereIn('id', array_column($this->entries, 'account_id'))->pluck('sub_account')->toArray();
            if(!in_array($this->customer_coa_level_4_id, $coas_data)) {
                $this->customer_account_present = false;
                $this->selected_month = '';
                $this->invoice_paid = 'f';
            }
        } else {
            $this->customer_account_present = false;
            $this->selected_month = '';
            $this->invoice_paid = 'f';
        }
    }

    public function searchAccounts($key)
    {
        if (env('AMS_BOOTSTRAP') == 'true') {
            $this->success = '';
            $this->dispatchBrowserEvent('open-modal');
        }
        $this->accounts = [];
        $this->search_accounts_modal = true;
        $this->key_id = $key;
        $this->emit('focusInput');

    }

    public function selectionAccount()
    {
        $contact = $this->accounts[$this->highlightIndex] ?? null;
        $contact_id = !empty($contact['id']) ? $contact['id'] : '';
        $contact_name = !empty($contact['name']) ? $contact['name'] : '';
        $this->chooseAccount($contact_id, $contact_name);
        $this->highlightIndex = 0;

    }

    public function chooseAccount($id, $name)
    {
        if(env('AMS_CUSTOMER', false) === true) {
            //finding if the account is from customers
            if ($this->customer_account_present === false) {
                $find_coa = ChartOfAccount::select('sub_account')->find($id);
                if (!empty($find_coa['sub_account']) && $find_coa['sub_account'] == $this->customer_coa_level_4_id) {
                    $this->customer_account_present = true;
                }
            }
        }

        $this->entries[$this->key_id]['account_id'] = $id;
        $this->entries[$this->key_id]['account_name'] = $name;
        $this->search_accounts_modal = false;
        $this->accounts = [];
        $this->search_accounts = '';

        if(env('AMS_CUSTOMER', false) === true) {
            $this->checkForExistenceOfCustomerAccount();
        }

        $this->entries[$this->key_id]['description'] = $this->entries[0]['description'];
        if (env('AMS_BOOTSTRAP') == 'true') {
            $this->dispatchBrowserEvent('close-modal');
        }
    }

    public function updatedSearchAccounts($value)
    {
        if (strlen($value) > 1) {
            $accounts = ChartOfAccount::where(function ($q) use ($value) {
                return $q->orWhere('name', 'LIKE', '%' . $value . '%')
                    ->orWhere('code', 'LIKE', '%' . $value . '%')
                    ->orWhere('type', 'LIKE', '%' . $value . '%');
            })->where('level', '5')->where('status', 't')
                ->limit(10)
                ->get();
            if ($accounts->isNotEmpty()) {
                $this->accounts = $accounts->toArray();
            } else {
                $this->accounts = [];
            }
        } else {
            $this->accounts = [];
        }
    }

    public function updated($name, $value)
    {
        $array = explode('.', $name);
        if (count($array) == 3) {

            if ($array[2] == 'debit') {
                $this->entries[$array[1]]['credit'] = 0;
                if (!is_numeric($value)) {
                    $this->entries[$array[1]]['debit'] = 0;
                }
            }
            if ($array[2] == 'credit') {
                $this->entries[$array[1]]['debit'] = 0;
                if (!is_numeric($value)) {
                    $this->entries[$array[1]]['credit'] = 0;
                }
            }

            if ($array[0] == 'attachment_entries') {
                $this->file_upload = false;
            }
        }

        if(env('AMS_CUSTOMER', false) === true) {
            if ($name == 'invoice_paid' && $value == 'f') {
                $this->selected_month = '';
            }
        }
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->accounts) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->accounts) - 1;
            return;
        }
        $this->highlightIndex--;
    }

    public function draft()
    {
        try {
            DB::beginTransaction();
            $this->validate();

            $entries = collect($this->entries);
            if ($entries->sum('debit') != $entries->sum('credit')) {
                $this->addError('voucher_no', 'Sum of debit and credit is not equal.');
                return false;
            }
//            if (Ledger::where('voucher_no', $this->voucher_no)->where('is_approve', 'f')->exists()) {
//                $this->addError('voucher_no', 'Unable to update because this entry already approved.');
//                return false;
//            }

            if (!empty($this->deleted)) {
                Ledger::where('is_approve', 'f')->whereIn('id', $this->deleted)->delete();
            }

            if(env('AMS_CUSTOMER', false) === true) {
                $this->addEntryInAmsCustomerPayment();
            }

            foreach ($this->entries as $entry) {
                if (isset($entry['id'])) {
                    //update
                    Ledger::find($entry['id'])->update([
                        'account_id' => !empty($entry['account_id']) ? $entry['account_id'] : null,
                        'voucher_no' => $this->voucher_no,
                        'debit' => $entry['debit'],
                        'credit' => $entry['credit'],
                        'description' => $entry['description'],
                        'posting_date' => !empty($this->posting_date) ? $this->formatDate($this->posting_date) : null,
                    ]);
                } else {
                    //created
                    Ledger::create([
                        'account_id' => !empty($entry['account_id']) ? $entry['account_id'] : null,
                        'voucher_no' => $this->voucher_no,
                        'debit' => $entry['debit'],
                        'credit' => $entry['credit'],
                        'description' => $entry['description'] ?? null,
                        'posting_date' => !empty($this->posting_date) ? $this->formatDate($this->posting_date) : null,
                        'posted_by' => Auth::user()->id
                    ]);
                }
            }
            if (!empty($this->deleted_attachment)) {
                LedgerAttachment::where('type', '0')->whereIn('id', $this->deleted_attachment)->delete();
            }
            foreach ($this->attachment_entries as $ae) {
                if (!empty($ae['file'])) {
                    if (is_object($ae['file'])) {
                        $path = $ae['file']->storePublicly(env('AWS_FOLDER') . 'accounts', 's3');
                        LedgerAttachment::create([
                            'account_id' => !empty($ae['account_id']) ? $ae['account_id'] : null,
                            'voucher_no' => $this->voucher_no,
                            'attachment' => $path
                        ]);
                    }
                } else {
                    if (!empty($ae['id'])) {
                        LedgerAttachment::find($ae['id'])->update([
                            'account_id' => !empty($ae['account_id']) ? $ae['account_id'] : null,
                        ]);
                    }

                }
            }

            $temp_entries = $this->getTempEntries();

            $this->posting_date = date('d M Y', strtotime($temp_entries->first()->posting_date));
            $this->voucher_no = $temp_entries->max('voucher_no');
            $this->entries = $temp_entries->toArray();

            $attachments = LedgerAttachment::where('type', '0')->where('voucher_no', $this->voucher_no)->get();
            if ($attachments->isNotEmpty()) {
                $this->attachment_entries = $attachments->toArray();
            }

            $this->success = 'Record has been updated.';
            DB::commit();
        } catch (\Exception $e) {
            $this->addError('voucher_no', $e->getMessage());
            DB::rollBack();
        }
        return true;
    }

    private function addEntryInAmsCustomerPayment()
    {
        $payment_entry_found = AmsCustomerPayment::where('voucher_no', $this->voucher_no)->where('temp_voucher', 't')->exists();

        if($this->customer_account_present && $this->invoice_paid == 't'){
            if(empty($this->selected_month)){
                throw new \Exception("If 'Invoice Paid' is set to 'Yes,' the selected month is required.");
            }
            if($payment_entry_found){
                AmsCustomerPayment::where('voucher_no', $this->voucher_no)->where('temp_voucher', 't')->update([
                    'voucher_no' => $this->voucher_no,
                    'month' => $this->selected_month,
                    'temp_voucher' => 't'
                ]);
            }else{
                AmsCustomerPayment::create([
                    'voucher_no' => $this->voucher_no,
                    'month' => $this->selected_month,
                    'temp_voucher' => 't'
                ]);
            }
        }else{
            if($payment_entry_found){
                AmsCustomerPayment::where('voucher_no', $this->voucher_no)->where('temp_voucher', 't')->delete();
            }
        }
    }

    private function formatDate($date)
    {

        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');

    }

    public function render()
    {
        return view('ams::livewire.journal.edit');
    }
}
