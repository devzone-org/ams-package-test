<?php

namespace Devzone\Ams\Http\Livewire\Journal\Payment;

use Carbon\Carbon;
use Devzone\Ams\Http\Traits\Searchable;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\PaymentReceiving;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Add extends Component
{
    use Searchable, WithFileUploads;

    public $nature;
    public $posting_date;
    public $first_account_name;
    public $first_account_id;
    public $second_account_name;
    public $second_account_id;
    public $amount;
    public $description;
    public $mode;
    public $instrument_no;
    public $attachment;
    public $success;

    protected $listeners = ['emitAccountId'];


    protected $rules = [
        'nature' => 'required',
        'posting_date' => 'required|date',
        'first_account_id' => 'required|integer',
        'second_account_id' => 'required|integer',
        'amount' => 'required|numeric',
        'description' => 'required|string',
        'mode' => 'required|string',
        'attachment' => 'nullable|max:1024',
    ];

    public function mount()
    {
        if (auth()->user()->can('2.payments.own') && !auth()->user()->can('2.payments.any')) {

            $this->second_account_id = Auth::user()->account_id;
            $this->second_account_name = Auth::user()->account_name;
        }
    }

    public function render()
    {
        return view('ams::livewire.journal.payments.add');
    }

    public function emitAccountId()
    {
        //$this->account_details = ChartOfAccount::find($this->account_id);
    }

    public function save()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $path = '';
            if (!empty($this->attachment)) {
                $path = $this->attachment->storePublicly(env('AWS_FOLDER') . 'accounts', 's3');
            }
            if (auth()->user()->can('2.payments.own') && !auth()->user()->can('2.payments.any')) {
                $this->second_account_id = Auth::user()->account_id;
            }
            if ($this->formatDate($this->posting_date) > date('Y-m-d')) {
                throw new \Exception('Future date not allowed.');
            }
            PaymentReceiving::create([
                'nature' => $this->nature,
                'posting_date' => $this->formatDate($this->posting_date),
                'first_account_id' => $this->first_account_id,
                'second_account_id' => $this->second_account_id,
                'amount' => $this->amount,
                'attachment' => $path,
                'mode' => $this->mode,
                'description' => $this->description,
                'instrument_no' => $this->instrument_no,
                'added_by' => Auth::user()->id
            ]);

            DB::commit();
            $this->reset(['nature', 'posting_date', 'first_account_id', 'second_account_id', 'second_account_name', 'first_account_name', 'amount', 'attachment','description', 'mode', 'instrument_no']);
            $this->success = 'Record has been added.';
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('nature', $e->getMessage());
        }
    }


    private function formatDate($date)
    {
        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');
    }
}
