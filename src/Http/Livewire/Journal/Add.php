<?php


namespace Devzone\Ams\Http\Livewire\Journal;


use Carbon\Carbon;
use Devzone\Ams\Helper\Voucher;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\Ledger;
use Devzone\Ams\Models\LedgerAttachment;
use Devzone\Ams\Models\TempLedger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use function Couchbase\defaultDecoder;

class Add extends Component
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
    public $account_list = [];
    public $highlightIndex = 0;

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

    public function mount()
    {
        $this->account_list = ChartOfAccount::where('level', 5)->orderBy('type')->get()->toArray();
        $this->accounts = $this->account_list;
        $temp_entries = $this->getTempEntries();


        if ($temp_entries->isNotEmpty()) {
            $this->posting_date = date('d M Y', strtotime($temp_entries->first()->posting_date));
            $this->voucher_no = $temp_entries->max('voucher_no');
            $this->entries = $temp_entries->toArray();
            $attachments = LedgerAttachment::where('type', '0')->where('voucher_no', $this->voucher_no)->get();

//            for($i=1; $i<= 4 - $temp_entries->count(); $i++){
//                $this->entries[] = $this->defaultEntries();
//            }

            if ($attachments->isNotEmpty()) {
                $this->attachment_entries = $attachments->toArray();

            }
        } else {
            $this->posting_date = date('d M Y');
            $this->voucher_no = Voucher::instance()->tempVoucherOnly();
            $this->entries[] = $this->defaultEntries();


        }

    }

    private function getTempEntries()
    {
        return TempLedger::from('temp_ledgers as tl')
            ->leftJoin('chart_of_accounts as coa', 'coa.id', '=', 'tl.account_id')
            ->where('tl.posted_by', Auth::user()->id)
            ->select('tl.*', DB::raw('CONCAT(coa.code, " - ",   coa.name) as account_name'))->get();
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
    }

    public function searchAccounts($key)
    {
        if(env('AMS_BOOTSTRAP') == 'true')
        {
            $this->dispatchBrowserEvent('open-modal');
        }
        $this->accounts = $this->account_list;
        $this->search_accounts_modal = true;
        $this->key_id = $key;
        $this->emit('focusInput');

    }

    public function selectionAccount()
    {
        $contact = $this->accounts[$this->highlightIndex] ?? null;
        $this->chooseAccount($contact['id'], $contact['name']);
        $this->highlightIndex = 0;

    }

    public function chooseAccount($id, $name)
    {
        $this->entries[$this->key_id]['account_id'] = $id;
        $this->entries[$this->key_id]['account_name'] = $name;
        $this->search_accounts_modal = false;

        $this->search_accounts = '';
        $this->entries[$this->key_id]['description'] = $this->entries[0]['description'];
        if(env('AMS_BOOTSTRAP') == 'true'){
            $this->dispatchBrowserEvent('close-modal');
        }

    }

    public function updatedSearchAccounts($value)
    {
        if (strlen($value) > 1) {
            $this->highlightIndex = 0;
            $accounts = ChartOfAccount::where(function ($q) use ($value) {
                return $q->orWhere('name', 'LIKE', '%' . $value . '%')
                    ->orWhere('code', 'LIKE', '%' . $value . '%')
                    ->orWhere('type', 'LIKE', '%' . $value . '%');
            })->where('level', '5')->where('status', 't')
                ->get();
            if ($accounts->isNotEmpty()) {
                $this->accounts = $accounts->toArray();
            } else {
                $this->accounts = $this->account_list;
            }
        } else {
            $this->accounts = $this->account_list;
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
        }
    }

    public function draft()
    {
        $this->resetErrorBag();
        try {
            DB::beginTransaction();
            //$this->validate();
            if (TempLedger::where('voucher_no', $this->voucher_no)
                ->where('posted_by', '!=', Auth::user()->id)->exists()) {
                $this->addError('voucher_no', 'Voucher # ' . $this->voucher_no . ' already in use. System have updated to new one kindly try again.');
                $this->voucher_no = Voucher::instance()->tempVoucherOnly();
                TempLedger::where('posted_by', Auth::user()->id)->update([
                    'voucher_no' => $this->voucher_no
                ]);
                return false;
            }

            if (!empty($this->deleted)) {
                TempLedger::where('posted_by', Auth::user()->id)->whereIn('id', $this->deleted)->delete();
            }

            foreach ($this->entries as $entry) {
                if (empty($entry['account_id']) && empty($entry['debit']) && empty($entry['credit']) && empty($entry['description'])) {
                    continue;
                }
                if (isset($entry['id'])) {
                    //update
                    TempLedger::find($entry['id'])->update([
                        'account_id' => !empty($entry['account_id']) ? $entry['account_id'] : null,
                        'voucher_no' => $this->voucher_no,
                        'debit' => $entry['debit'],
                        'credit' => $entry['credit'],
                        'description' => $entry['description'],
                        'posting_date' => !empty($this->posting_date) ? $this->formatDate($this->posting_date) : null,
                    ]);
                } else {
                    //created
                    TempLedger::create([
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
                    $path = $ae['file']->storePublicly(env('AWS_FOLDER') . 'accounts', 's3');
                    LedgerAttachment::create([
                        'account_id' => !empty($ae['account_id']) ? $ae['account_id'] : null,
                        'voucher_no' => $this->voucher_no,
                        'attachment' => $path
                    ]);
                } else {
                    if (!empty($ae['account_id'])) {
                        LedgerAttachment::find($ae['account_id'])->update([
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

    private function formatDate($date)
    {
        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');
    }

    public function deleteAll()
    {
        TempLedger::where('posted_by', Auth::user()->id)->delete();
        LedgerAttachment::where('type', '0')->where('voucher_no', $this->voucher_no)->delete();
        $this->reset('entries', 'attachment_entries');
    }

    public function posted()
    {

        $this->validate();
        try {
            DB::beginTransaction();

            if (Ledger::where('voucher_no', $this->voucher_no)
                ->where('is_approve', 'f')->exists()) {
                $this->addError('voucher_no', 'Voucher # ' . $this->voucher_no . ' already in use. System have updated to new one kindly try again.');
                $this->voucher_no = Voucher::instance()->tempVoucherOnly();
                return false;
            }


            $entries = collect($this->entries);


            if ($entries->sum('debit') != $entries->sum('credit')) {
                $this->addError('voucher_no', 'Sum of debit and credit is not equal.');
                return false;
            }

            foreach ($this->entries as $entry) {
                if (empty($entry['account_id']) && empty($entry['debit']) && empty($entry['credit']) && empty($entry['description'])) {
                    continue;
                }

                $date = Carbon::now()->subDays(env('JOURNAL_RESTRICTION_DAYS'))->toDateString();
                if (!auth()->user()->can('2.create.transfer.any-date')) {

                    if ($date > $this->formatDate($this->posting_date)) {
                        throw new \Exception('Posting date must be equal or greater than ' . date('d M, Y', strtotime($date)));
                    }
                }

                if (auth()->user()->can('2.create.transfer.restricted-date')) {
                    if (Carbon::now()->toDateString() > $this->posting_date) {
                        $diff_in_days = Carbon::parse($this->posting_date)->diffInDays(Carbon::now());

                        if (empty(env("AMS_RESTRICT_DATE"))) {
                            $restrict_days = 3;
                        } else {
                            $restrict_days = env("AMS_RESTRICT_DATE");
                        }

                        if ($diff_in_days > $restrict_days) {
                            throw new \Exception("You can't approve the record after " . ($restrict_days == 1 ? $restrict_days . " day" : $restrict_days . " days") . " of posting  date.");
                        }
                    }
                }

                Ledger::create([
                    'account_id' => $entry['account_id'],
                    'voucher_no' => $this->voucher_no,
                    'debit' => $entry['debit'],
                    'credit' => $entry['credit'],
                    'description' => $entry['description'],
                    'posting_date' => $this->formatDate($this->posting_date),
                    'posted_by' => Auth::user()->id
                ]);
            }

            if (!empty($this->deleted_attachment)) {
                LedgerAttachment::where('type', '0')->whereIn('id', $this->deleted_attachment)->delete();
            }

            foreach ($this->attachment_entries as $ae) {
                if (isset($ae['file'])) {
                    $path = $ae['file']->storePublicly(env('AWS_FOLDER') . 'accounts', 's3');
                    LedgerAttachment::create([
                        'account_id' => !empty($ae['account_id']) ? $ae['account_id'] : null,
                        'voucher_no' => $this->voucher_no,
                        'attachment' => $path
                    ]);
                } else {
                    if (isset($ae['id'])) {
                        LedgerAttachment::find($ae['id'])->update([
                            'account_id' => !empty($ae['account_id']) ? $ae['account_id'] : null,
                        ]);
                    }
                }
            }


            TempLedger::where('posted_by', Auth::user()->id)->delete();
            $this->reset('entries', 'attachment_entries');
            $this->voucher_no = Voucher::instance()->tempVoucher()->updateCounter();
            $this->addEntry();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('voucher_no', $e->getMessage());
        }
    }

    public function addEntry()
    {
        $this->entries[] = $this->defaultEntries();
    }

    public function render()
    {
        return view('ams::livewire.journal.add');
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
}
