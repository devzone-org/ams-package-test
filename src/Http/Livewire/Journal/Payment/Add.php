<?php

namespace Devzone\Ams\Http\Livewire\Journal\Payment;

use Devzone\Ams\Http\Traits\Searchable;
use Devzone\Ams\Models\PaymentReceiving;
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




            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('nature', $e->getMessage());
        }
    }
}
