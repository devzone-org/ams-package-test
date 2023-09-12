<?php


namespace Devzone\Ams\Http\Livewire\Journal;


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
        $this->account_list = ChartOfAccount::where('level', 5)->orderBy('type')->get()->toArray();
        $this->accounts = $this->account_list;
        $temp_entries = $this->getTempEntries();


        if ($temp_entries->isNotEmpty()) {
            $this->posting_date = date('d M Y', strtotime($temp_entries->first()->posting_date));
            $this->voucher_no = $temp_entries->max('voucher_no');
            $this->entries = $temp_entries->toArray();
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
        $this->chooseAccount($contact['id'], $contact['name']);
        $this->highlightIndex = 0;

    }

    public function chooseAccount($id, $name)
    {
        $this->entries[$this->key_id]['account_id'] = $id;
        $this->entries[$this->key_id]['account_name'] = $name;
        $this->search_accounts_modal = false;
        $this->accounts = [];
        $this->search_accounts = '';
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
                ->get();
            if ($accounts->isNotEmpty()) {
                $this->accounts = $accounts->toArray();
            } else {
                $this->accounts = $this->account_list;
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
