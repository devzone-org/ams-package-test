<?php

namespace Devzone\Ams\Http\Livewire\Reports;
use Devzone\Ams\Models\ChartOfAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use function Couchbase\defaultDecoder;

class PnlTemplateReport extends Component
{
    public $from_date;
    public $to_date;
    public $closing_vouchers = 'hide';
    public $heading = [];
    public $report = [];
    public $all_templates = [];
    public $template_id;
    public $year;

    protected $rules = [
        'template_id' => 'required',
    ];

    protected $messages = [
        'template_id.required' => 'Template type is required.',
    ];


    public function mount()
    {
        $this->from_date = Carbon::now()->startOfMonth()->format('d M Y');
        $this->to_date = date('d M Y');
        $this->all_templates = \Devzone\Ams\Models\PnlTemplateManager::get()->keyBy('id')->toArray();
//        $this->search();
    }

    public function render()
    {
        return view('ams::livewire.reports.pnl-template-report');
    }

    private function formatDate($date)
    {
        return Carbon::createFromFormat('d M Y', $date)
            ->format('Y-m-d');
    }

    public function search()
    {
        $this->validate();
        try {
            $from = Carbon::parse($this->from_date);
            $to = Carbon::parse($this->to_date);
            if ($from->month != $to->month) {
                throw new \Exception('From Date and To Date must be in the same month.');
            }
            $template = $this->all_templates[$this->template_id] ?? null;
            if (empty($template)) {
                throw new \Exception('Template not found.');
            }
            $lvl_5_account_ids = array_merge($template['income_accounts'], $template['expense_accounts']);
            $report = \Devzone\Ams\Models\Ledger::from('ledgers as l')
                ->join('chart_of_accounts as coa', 'coa.id', '=', 'l.account_id')
                ->when(!empty($this->closing_vouchers) && strtolower($this->closing_vouchers) == 'hide', function ($q) {
                    return $q->leftJoin('closing_summary_accounts as dc','dc.voucher_no','l.voucher_no')
                        ->whereNull('dc.voucher_no');
                })
                ->select(
                    DB::raw('sum(l.debit) as debit'),
                    DB::raw('sum(l.credit) as credit'),
                    'coa.name',
                    'coa.type',
                    'coa.nature',
                    'coa.is_contra',
                    'coa.reference',
                    'coa.sub_account',
                    'l.account_id',
                    DB::raw("DATE_FORMAT(l.posting_date,'%d %M') as month"),
                    DB::raw("DATE_FORMAT(l.posting_date,'%Y-%m-%d') as date"))
                ->where('l.posting_date', '>=', $this->formatDate($this->from_date))
                ->where('l.posting_date', '<=', $this->formatDate($this->to_date))
                ->whereIn('l.account_id', $lvl_5_account_ids)
                ->where('l.is_approve', 't')
                ->whereIn('coa.type', ['Income', 'Expenses'])
                ->groupBy(DB::raw("DATE_FORMAT(l.posting_date,'%d %M')"))
                ->groupBy('l.account_id')
                ->orderBy('coa.name', 'asc')
                ->get();
            $account_ids = array_unique($report->pluck('sub_account')->toArray());
            $accounts = ChartOfAccount::whereIn('id', $account_ids)->get();
            $this->heading = $report->groupBy('month')->sortKeys()->keys()->toArray();
            $pnl = [];
            foreach ($report as $r) {

                if ($r->nature == 'd') {
                    if ($r->is_contra == 'f') {
                        $balance = $r->debit - $r->credit;
                    } else {
                        $balance = -($r->credit - $r->debit);
                    }
                } else {
                    if ($r->is_contra == 'f') {
                        $balance = $r->credit - $r->debit;
                    } else {
                        $balance = -($r->debit - $r->credit);
                    }
                }
                $p_ref = $accounts->firstWhere('id', $r->sub_account);
                if (!empty($p_ref)) {
                    $p_ref = $p_ref->reference;
                } else {
                    $p_ref = null;
                }
                $pnl[] = [
                    'name' => $r->name,
                    'type' => $r->type,
                    'nature' => $r->nature,
                    'is_contra' => $r->is_contra,
                    'month' => $r->month,
                    'balance' => $balance,
                    'account_id' => $r->account_id,
                    'reference' => $r->reference,
                    'p_ref' => $p_ref,
                    'date' => $r->date
                ];
            }
            $this->report = $pnl;
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }


    }
    public function resetSearch(){
        $this->from_date = Carbon::now()->startOfMonth()->format('d M Y');
        $this->to_date = date('d M Y');
        $this->template_id = null;
    }

}