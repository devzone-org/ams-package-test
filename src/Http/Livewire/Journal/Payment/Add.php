<?php

namespace Devzone\Ams\Http\Livewire\Journal\Payment;

use Carbon\Carbon;
use Devzone\Ams\Http\Traits\Searchable;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\PaymentReceiving;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

    public $payment_accounts, $from_account_id, $to_account_id;

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
        $this->posting_date = date('d M Y');

        if (auth()->user()->can('2.payments.own') && !auth()->user()->can('2.payments.any')) {
            $this->second_account_id = Auth::user()->account_id;
            $this->second_account_name = Auth::user()->account_name;
        }

        $this->payment_accounts = \App\Models\ChartOfAccount::whereIn('sub_account', function ($q) {
            $q->select('id')->from('chart_of_accounts')->where('sub_account', function ($p) {
                $p->select('id')->from('chart_of_accounts')->where('level', 3)->where('name', 'Cash and Cash Equivalents');
            });
        })
            ->get()
            ->groupBy('sub_account')
            ->toArray();
    }

    public function updatedNature()
    {
        $this->reset([
            'first_account_id',
            'second_account_id',
            'second_account_name',
            'first_account_name',
            'amount',
            'attachment',
            'description',
            'mode',
            'instrument_no',
            'from_account_id',
            'to_account_id',
            'posting_date',
        ]);

        $this->posting_date = date('d M Y');

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
        $this->success = null;
        $lock = Cache::lock('savePayment.' . $this->first_account_id . $this->second_account_id, 60);
        $this->validate();

        try {
            DB::beginTransaction();

            if ($lock->get()) {
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
                $date = Carbon::now()->subDays(env('JOURNAL_RESTRICTION_DAYS'))->toDateString();
                if (!auth()->user()->can('2.create.transfer.any-date')) {

                    if ($date > $this->formatDate($this->posting_date)) {
                        throw new \Exception('Posting date must be equal or greater than ' . date('d M, Y', strtotime($date)));
                    }
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
                $this->reset(['nature', 'first_account_id', 'second_account_id', 'second_account_name', 'first_account_name', 'amount', 'attachment', 'description', 'mode', 'instrument_no']);
                $this->success = 'Record has been added.';
                optional($lock)->release();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            optional($lock)->release();
            $this->addError('nature', $e->getMessage());
        }
    }

    private function formatDate($date)
    {
        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');
    }

    public function transferEntry()
    {
        $this->success = null;
        $lock = Cache::lock('transferPayment.' . $this->from_account_id . $this->to_account_id, 60);

        $this->validate([
                'posting_date' => 'required|date',
                'amount' => 'required|numeric',
                'description' => 'required|string',
                'from_account_id' => 'required|integer',
                'to_account_id' => 'required|integer',
            ]
            , [],
            [
                'posting_date' => 'Transaction Date',
                'amount' => 'Transfer Amount',
                'description' => 'Description',
                'from_account_id' => 'Transfer From',
                'to_account_id' => 'Transfer To',
            ]);

        try {
            DB::beginTransaction();

            if ($lock->get()) {
                if (auth()->user()->cannot('2.transfer-entry')) {
                    throw new \Exception('You do not have permission to perform this action.');
                }

                if ($this->formatDate($this->posting_date) > date('Y-m-d')) {
                    throw new \Exception('Future date not allowed.');
                }

                if ($this->from_account_id == $this->to_account_id) {
                    throw new \Exception('Both accounts cannot be same for the transfer.');
                }

                $date = Carbon::now()->subDays(env('JOURNAL_RESTRICTION_DAYS'))->toDateString();

                if (!auth()->user()->can('2.create.transfer.any-date')) {
                    if ($date > $this->formatDate($this->posting_date)) {
                        throw new \Exception('Posting date must be equal or greater than ' . date('d M, Y', strtotime($date)));
                    }
                }

                PaymentReceiving::create([
                    'nature' => $this->nature,
                    'posting_date' => $this->formatDate($this->posting_date),
                    'first_account_id' => $this->to_account_id,
                    'second_account_id' => $this->from_account_id,
                    'amount' => $this->amount,
                    'mode' => 'cash',
                    'description' => $this->description,
                    'added_by' => Auth::user()->id
                ]);

                DB::commit();
                $this->reset(['nature', 'first_account_id', 'second_account_id', 'second_account_name', 'first_account_name', 'amount', 'attachment', 'description', 'mode', 'instrument_no']);
                $this->success = 'Record has been added.';
                optional($lock)->release();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            optional($lock)->release();
            $this->addError('nature', $e->getMessage());
        }
    }

    public function openSaveConfirmModal()
    {
        $this->validate([
                'posting_date' => 'required|date',
                'amount' => 'required|numeric',
                'description' => 'required|string',
                'from_account_id' => 'required|integer',
                'to_account_id' => 'required|integer',
            ]
            , [],
            [
                'posting_date' => 'Transaction Date',
                'amount' => 'Transfer Amount',
                'description' => 'Description',
                'from_account_id' => 'Transfer From',
                'to_account_id' => 'Transfer To',
            ]);
    }
}
