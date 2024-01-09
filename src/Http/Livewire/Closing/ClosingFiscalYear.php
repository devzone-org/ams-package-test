<?php


namespace Devzone\Ams\Http\Livewire\Closing;


use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\ClosingSummaryAccounts;
use Devzone\Ams\Models\Ledger;
use DB;
use Livewire\Component;

class ClosingFiscalYear extends Component
{
    public $closing_year;
    public $selected_year;
    public $entries_confirm, $agree_confirm;
    public $success;
    public $closing_data, $closing_data_array;
    public $fiscal_years = [];
    public $summary_account;

    protected $rules = [
        'closing_year' => 'required',
        'entries_confirm' => 'required',
        'agree_confirm' => 'required'
    ];
    protected $validationAttributes = [
        'closing_year' => 'Closing Year',
        'entries_confirm' => 'Confirm All Entries',
        'agree_confirm' => 'Agree'
    ];

    public function mount()
    {
        $this->getFiscalYears();
    }

    public function getFiscalYears()
    {
        try {
            $first_entry_date = Ledger::from('ledgers as l')
                ->join('chart_of_accounts as coa', 'coa.id', 'l.account_id')
                ->where('is_approve', 't')
                ->whereIn('coa.type', ['Income', 'Expenses'])
                ->orderby('posting_date', 'asc')
                ->first()->posting_date;

            $first_entry_year = date('Y', strtotime($first_entry_date));
            $closing_dates = ['to' => '06-30', 'from' => '07-01'];
            $posting_date_to = \Carbon\Carbon::createFromFormat('Y-m-d', $first_entry_year . '-' . $closing_dates['to']);

            if ((date('Y-m-d')) <= (date('Y-m-d', strtotime(date('Y') . '-' . $closing_dates['to'])))) {
                $ending_year = date('Y');
            } else {
                $ending_year = date('Y', strtotime('+1 year'));
            }

            if ($posting_date_to > $first_entry_date) {
                $from_year = date('Y', strtotime('-1 year', strtotime($first_entry_date)));
                $to_year = date('y', strtotime($first_entry_date));
            } else {
                $from_year = date('Y', strtotime($first_entry_date));
                $to_year = date('y', strtotime('+1 year', strtotime($first_entry_date)));
            }

            while ($from_year < $ending_year) {
                $this->fiscal_years[] = ['year' => $from_year . '-' . $to_year, 'from' => $from_year . '-' . $closing_dates['from'], 'to' => '20' . $to_year . '-' . $closing_dates['to']];
                ++$from_year;
                ++$to_year;
            }
        } catch (\Exception $ex) {
            $this->addError('error', $ex->getMessage());
        }
    }

    public function checkAndGetRecord()
    {
        $this->selected_year = collect($this->fiscal_years)->where('year', $this->closing_year)->first();
        $get_previous_year = collect($this->fiscal_years)->where('to', '<', $this->selected_year['to'])->first();


        if ($get_previous_year != null) {

            $exists = ClosingSummaryAccounts::where('fiscal_year', $get_previous_year['year'])->exists();

            if (!$exists) {

                throw new \Exception('Fiscal year ' . $get_previous_year['year'] . ' not closed.');
            }
        }

        $this->closing_data = Ledger::from('ledgers as l')
            ->where('l.is_approve', 't')
            ->join('chart_of_accounts as coa', 'coa.id', 'l.account_id')
            ->whereIn('coa.type', ['Income', 'Expenses'])
            ->whereDate('l.posting_date', '>=', $this->selected_year['from'])
            ->whereDate('l.posting_date', '<=', $this->selected_year['to'])
            ->selectRaw('sum(l.debit) as debit, sum(l.credit) as credit, coa.name, coa.type , l.account_id')
            ->orderBy('l.posting_date', 'asc')
            ->groupBy('l.account_id')
            ->get();

        $this->closing_data_array = $this->closing_data->toArray();
    }

    public function getAndUpdateVoucher()
    {
        $voucher = \Devzone\Ams\Models\Voucher::where('name', 'voucher')
            ->select('value')
            ->first()->value;
        $temp = \Devzone\Ams\Models\Voucher::where('name', 'voucher')
            ->update(['value' => $voucher + 1]);

        return $voucher;
    }

    public function getSummary()
    {
        $this->validate([
            'closing_year' => 'required',
            'entries_confirm' => 'required',
        ]);

        $this->success = null;
        $this->closing_data = null;

        try {

            $this->checkAndGetRecord();

            $this->success = 'Successfully loaded closing summary A/C.';
        } catch (\Exception $ex) {
            $this->addError('error', $ex->getMessage());
        }
    }

    public function closeFiscalYear()
    {
        $this->validate();

        $this->success = null;

        try {

            $this->checkAndGetRecord();

            DB::beginTransaction();

            $debit_voucher_id = $this->getAndUpdateVoucher();
            $credit_voucher_id = $this->getAndUpdateVoucher();
            $equity_voucher_id = $this->getAndUpdateVoucher();

            foreach ($this->closing_data as $data) {

                $this->closingSummaryAccount($data, ['dvid' => $debit_voucher_id, 'cvid' => $credit_voucher_id], $this->selected_year);

                $debit = 0;
                $credit = 0;

                if ($data->type == 'Expenses') {

                    $debit = $data->debit - $data->credit;
                    $voucher_id = $debit_voucher_id;
                } elseif ($data->type == 'Income') {

                    $credit = $data->credit - $data->debit;
                    $voucher_id = $credit_voucher_id;
                }

                Ledger::create([
                    'account_id' => $data->account_id,
                    'voucher_no' => $voucher_id,
                    'type' => $data->type,
                    'debit' => $debit,
                    'credit' => $credit,
                    'description' => 'Fiscal Year  ' . $this->selected_year['year'] . ' Closed to Summary Account.',
                    'posting_date' => date('Y-m-d', strtotime($this->selected_year['to'])),
                    'posted_by' => \Auth::user()->id,
                    'is_approve' => 't',
                    'approved_at' => date('Y-m-d'),
                    'approved_by' => \Auth::user()->id
                ]);
            }

            $details = $this->closingEquityEntries($this->closing_data);
            $coa = ChartOfAccount::where('type', 'Equity')
                ->where('level', '5')
                ->where('is_contra', 'f')
                ->get();

            $total_partner = $coa->count();

            foreach ($coa as $data) {

                Ledger::create([
                    'account_id' => $data->id,
                    'voucher_no' => $equity_voucher_id,
                    'type' => $data->type,
                    'debit' => $details['debit'] > 0 ? ($details['debit'] / $total_partner) : 0,
                    'credit' => $details['credit'] > 0 ? ($details['credit'] / $total_partner) : 0,
                    'description' => 'Fiscal Year  ' . $this->selected_year['year'] . ' Closed to Summary Account.',
                    'posting_date' => date('Y-m-d', strtotime($this->selected_year['to'])),
                    'posted_by' => \Auth::user()->id,
                    'is_approve' => 't',
                    'approved_at' => date('Y-m-d'),
                    'approved_by' => \Auth::user()->id
                ]);
            }

            DB::commit();

            $this->success = 'Fiscal Year  ' . $this->selected_year['year'] . ' has been closed successfully.';
            $this->closing_data = null;

            unset($this->closing_year, $this->entries_confirm, $this->agree_confirm);
        } catch (\Exception $ex) {

            DB::rollBack();
            $this->addError('error', $ex->getMessage() . 'Unable to close fiscal year.');
        }
    }

    public function closingSummaryAccount($data, $voucher_id, $year)
    {
        $debit = 0;
        $credit = 0;

        if ($data->type == 'Expenses') {

            $debit = $data->debit - $data->credit;
            $voucher_id = $voucher_id['dvid'];
        } elseif ($data->type == 'Income') {

            $credit = $data->credit - $data->debit;
            $voucher_id = $voucher_id['cvid'];
        }

        ClosingSummaryAccounts::create([
            'account_id' => $data->account_id,
            'voucher_no' => $voucher_id,
            'type' => $data->type,
            'fiscal_year' => $year['year'],
            'debit' => $debit ?? 0,
            'credit' => $credit ?? 0,
            'posting_date' => date('Y-m-d', strtotime($year['to'])),
            'description' => 'Fiscal Year  ' . $year['year'] . ' Closed to Summary Account.',
            'posted_by' => \Auth::user()->id
        ]);
    }

    public function closingEquityEntries($closing)
    {
        $loss = 0;
        $profit = 0;

        $debit = $closing->where('type', 'Expenses')->sum('debit') - $closing->where('type', 'Expenses')->sum('credit');
        $credit = $closing->where('type', 'Income')->sum('credit') - $closing->where('type', 'Income')->sum('debit');

        if ($debit > $credit) {
            $loss = $debit - $credit;
        } else if ($credit > $debit) {
            $profit = $credit - $debit;
        }

        return ['debit' => $loss, 'credit' => $profit];
    }

    public function render()
    {
        $this->summary_account = ClosingSummaryAccounts::groupBy('fiscal_year')
            ->select('fiscal_year', 'voucher_no')
            ->get();

        return view('ams::livewire.closing.closing-fiscal-year');
    }
}
