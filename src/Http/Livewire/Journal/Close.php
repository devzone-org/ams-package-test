<?php


namespace Devzone\Ams\Http\Livewire\Journal;


use App\Models\User;
use Devzone\Ams\Helper\GeneralJournal;
use Devzone\Ams\Helper\Voucher;
use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\DayClosing;
use Devzone\Ams\Models\Ledger;
use Devzone\Ams\Models\LedgerAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Livewire\WithFileUploads;

class Close extends Component
{
    use WithFileUploads;

    public $users = [];
    public $user_account_id;
    public $current_user = [];
    public $denomination_counting = [];
    public $opening_balance;
    public $opening_balance_date;
    public $closing_voucher;
    public $closing_balance = [];
    public $closing_balance_heads = [];
    public $difference;
    public $retained_cash = 0;
    public $transfers = [];
    public $confirm_popup = false;
    public $transfer_id;
    public $attachment;
    public $description;

    protected $rules = [
        'transfer_id' => 'required|integer'
    ];
    protected $listeners = ['proceedClosing'];

    public function mount()
    {
        $this->users = User::from('users as u')->join('chart_of_accounts as coa', 'coa.id', '=', 'u.account_id')
            ->select('u.*')->get()->toArray();

        $this->denomination_counting = [
            ['currency' => '5000', 'count' => '0', 'total' => '0'],
            ['currency' => '1000', 'count' => '0', 'total' => '0'],
            ['currency' => '500', 'count' => '0', 'total' => '0'],
            ['currency' => '100', 'count' => '0', 'total' => '0'],
            ['currency' => '50', 'count' => '0', 'total' => '0'],
            ['currency' => '20', 'count' => '0', 'total' => '0'],
            ['currency' => '10', 'count' => '0', 'total' => '0'],
            ['currency' => '5', 'count' => '0', 'total' => '0'],
            ['currency' => '2', 'count' => '0', 'total' => '0'],
            ['currency' => '1', 'count' => '0', 'total' => '0']
        ];

        $this->transfers = ChartOfAccount::whereIn('sub_account', ['11', '12'])->get()->toArray();
    }


    public function render()
    {
        return view('ams::livewire.journal.close');
    }

    public function updatedUserAccountId($value)
    {
        $this->reset(['current_user', 'opening_balance', 'closing_balance']);

        $user = collect($this->users)->firstWhere('account_id', $value);
        if (!empty($user)) {
            $this->current_user = $user;
            $closing = DayClosing::where('account_id', $value)->orderBy('id', 'desc')->first();


            if (!empty($closing)) {


                $this->opening_balance = $closing['cash_retained'];
                $this->opening_balance_date = $closing['date'];
                $this->closing_voucher = $closing['voucher_no'];
            }


            $closing_balance = Ledger::where('account_id', $value)
                ->when(!empty($closing), function ($q) use ($closing) {
                    return $q->where('voucher_no', '>', $closing['voucher_no']);
                })->select(DB::raw('sum(debit-credit) as balance'), 'reference')
                ->groupBy('reference')->get();
            $this->closing_balance = $closing_balance->toArray();

            $this->closing_balance_heads = $closing_balance->pluck('reference')->toArray();

        }
    }


    public function updated($name, $value)
    {
        $array = explode('.', $name);
        if (count($array) == 3) {
            if ($array[0] == 'denomination_counting') {
                if ($value > 0) {
                    $this->denomination_counting[$array[1]]['total'] =
                        $this->denomination_counting[$array[1]]['currency'] * $value;
                } else {
                    $this->denomination_counting[$array[1]]['total'] =
                        $this->denomination_counting[$array[1]]['currency'] * 0;
                }

                $this->difference = (collect($this->denomination_counting)->sum('total') - collect($this->closing_balance)->sum('balance') - $this->opening_balance);
            }
        }
    }


    public function proceedClosing()
    {
        $this->validate();
        $lock = Cache::lock('day-closing' . auth()->id(), 60);
        try {
            if ($lock->get()) {
                if ((collect($this->closing_balance)->sum('balance') + $this->opening_balance) < -1000) {
                    throw new \Exception('Closing Balance should be greater than -1000.');
                }

                $closing_balance = Ledger::where('account_id', $this->user_account_id)
                    ->select(DB::raw('sum(debit-credit) as balance'))
                    ->first();


                if (round(collect($this->closing_balance)->sum('balance') + $this->opening_balance, 2) != round($closing_balance['balance'], 2)) {
                    throw new \Exception('Closing Balance has been changed please refresh the page and logout the Closing ID.');
                }


                DB::beginTransaction();
                $total_denomination = collect($this->denomination_counting)->sum('total');
                $transfer_amount = $total_denomination - $this->retained_cash;
                $description = "[TILL CLOSING: " . date('d M Y h:i A') . "]; [Teller: " . $this->current_user['name'] .
                    " Till closed by: " . Auth::user()->name . "][Transferring PKR " . number_format($transfer_amount, 2) . " to " . collect($this->transfers)->firstWhere('id', $this->transfer_id)['name'] . " from till of Teller '" . $this->current_user['name'] . "'. Cash Retained PKR " .
                    number_format($this->retained_cash, 2) . " in till of " . $this->current_user['name'] . "]";

                if (!empty($this->description)) {
                    $description .= ' Description: ' . $this->description;
                }


                if ($total_denomination > 0) {
                    $vno = Voucher::instance()->voucher()->get();
                    if (empty($this->difference)) {

                        GeneralJournal::instance()->account($this->user_account_id)->credit($transfer_amount + $this->retained_cash)->voucherNo($vno)
                            ->date(date('Y-m-d'))->approve()->description($description)->execute();
                        GeneralJournal::instance()->account($this->transfer_id)->debit($transfer_amount)->voucherNo($vno)
                            ->date(date('Y-m-d'))->approve()->reference('day close')->description($description)->execute();
                        if ($this->retained_cash > 0) {
                            GeneralJournal::instance()->account($this->user_account_id)->debit($this->retained_cash)->voucherNo($vno)
                                ->date(date('Y-m-d'))->approve()->description($description)->execute();
                        }
                    } else if ($this->difference > 0) {

                        $description .= " Surplus PKR " . number_format($this->difference, 2) . "/-";
                        GeneralJournal::instance()->account($this->user_account_id)->credit($transfer_amount + $this->retained_cash)->voucherNo($vno)
                            ->date(date('Y-m-d'))->approve()->description($description)->execute();
                        GeneralJournal::instance()->account(67)->credit($this->difference)->voucherNo($vno)
                            ->date(date('Y-m-d'))->approve()->description($description)->execute();

                        if ($this->retained_cash > 0) {
                            GeneralJournal::instance()->account($this->user_account_id)->debit($this->retained_cash)->voucherNo($vno)
                                ->date(date('Y-m-d'))->approve()->description($description)->execute();
                        }
                        GeneralJournal::instance()->account($this->transfer_id)->debit($transfer_amount)->voucherNo($vno)
                            ->date(date('Y-m-d'))->approve()->reference('day close')->description($description)->execute();
                        GeneralJournal::instance()->account($this->user_account_id)->debit($this->difference)->voucherNo($vno)
                            ->date(date('Y-m-d'))->approve()->description($description)->execute();


                    } else if ($this->difference < 0) {
                        $description .= " Shortage PKR " . number_format(abs($this->difference), 2) . "/-";
                        GeneralJournal::instance()->account($this->user_account_id)->credit($transfer_amount + $this->retained_cash)->voucherNo($vno)
                            ->date(date('Y-m-d'))->approve()->description($description)->execute();

                        GeneralJournal::instance()->account(82)->debit(abs($this->difference))->voucherNo($vno)
                            ->date(date('Y-m-d'))->approve()->description($description)->execute();
                        if ($this->retained_cash > 0) {
                            GeneralJournal::instance()->account($this->user_account_id)->debit($this->retained_cash)->voucherNo($vno)
                                ->date(date('Y-m-d'))->approve()->description($description)->execute();
                        }
                        GeneralJournal::instance()->account($this->transfer_id)->debit($transfer_amount)->voucherNo($vno)
                            ->date(date('Y-m-d'))->approve()->reference('day close')->description($description)->execute();
                        GeneralJournal::instance()->account($this->user_account_id)->credit(abs($this->difference))->voucherNo($vno)
                            ->date(date('Y-m-d'))->approve()->description($description)->execute();

                    }
                    $attachment = null;
                    if (!empty($this->attachment)) {
                        $attachment = $this->attachment->storePublicly(config('app.aws_folder') . 'day-closing', 's3');
                        LedgerAttachment::create([
                            'account_id' => $this->user_account_id,
                            'voucher_no' => $vno,
                            'attachment' => $attachment,
                            'type' => 'day_closing'
                        ]);
                    }


                    $array['account_id'] = $this->user_account_id;
                    foreach ($this->closing_balance as $key => $c) {
                        $array['ref_' . ($key + 1)] = $c['reference'];
                        $array['ref_amount_' . ($key + 1)] = $c['balance'];
                    }
                    $array['close_by'] = Auth::id();
                    $array['closing_balance'] = collect($this->closing_balance)->sum('balance') + $this->opening_balance;
                    $array['physical_cash'] = $total_denomination;
                    $array['cash_retained'] = $this->retained_cash;
                    $array['date'] = date('Y-m-d');
                    $array['voucher_no'] = $vno;
                    $array['transfer_to'] = $this->transfer_id;
                    $array['attachment'] = $attachment ?? null;
                    $array['description'] = $this->description;

                    DayClosing::create($array);
                } else {
                    throw new \Exception('Denomination cash must be greater than 0.');
                }
                DB::commit();
            }
            $lock->release();
            $this->redirect('/accounts/accountant/day-close');
        } catch (\Exception $e) {
            DB::rollBack();
            $lock->release();
            $this->addError('denomination_counting', $e->getMessage());
            $this->confirm_popup = false;
        }
    }
}
