<?php

namespace Devzone\Ams\Http\Livewire\Reports;
use Devzone\Ams\Models\ChartOfAccount;
use Livewire\Component;
class PnlTemplateManager extends Component
{

    public $detail = [];
    public $expense_accounts = [];
    public $income_accounts = [];
    public $pnl_template_lists = [];
    public $is_edit = false;
    public $delete_modal = false;
    public $success, $error_message, $delete_id;

    protected function rules(): array
    {
        return [
            'detail.report_name' => 'required|string|unique:pnl_template_managers,report_name,' . ($this->detail['id'] ?? '') . ',id|max:255',
            'detail.expense_accounts' => 'required|array',
            'detail.income_accounts' => 'required|array',
        ];
    }

    protected $validationAttributes = [
        'detail.income_accounts' => 'Income Accounts',
        'detail.expense_accounts' => 'Expense Accounts',
        'detail.report_name' => 'Report Name',
    ];

    public function mount(): void
    {
        $this->search();
        $this->income_accounts = ChartOfAccount::where('type','Income')->where('level','5')->pluck('name','id')->toArray();
        $this->expense_accounts = ChartOfAccount::where('type','Expenses')->where('level','5')->pluck('name','id')->toArray();
    }
    protected function search(){
        $this->pnl_template_lists = \Devzone\Ams\Models\PnlTemplateManager::join('users as u', 'u.id', 'pnl_template_managers.added_by')
            ->select('pnl_template_managers.*', 'u.name as created_by_name')
            ->orderByDesc('pnl_template_managers.id')
            ->get()->toArray();
    }

    public function saveTemplate(): void
    {
        $this->validate();
        try {
            if (!$this->is_edit) {
                $this->detail['added_by'] = auth()->user()->id;
                \Devzone\Ams\Models\PnlTemplateManager::create([
                    'report_name'       => $this->detail['report_name'],
                    'income_accounts'   => $this->detail['income_accounts'],
                    'expense_accounts'  => $this->detail['expense_accounts'],
                    'added_by'          => auth()->user()->id,
                ]);
                $this->success = 'Template Added Successfully';
                $this->dispatchBrowserEvent('reset-select-2');
                $this->reset(['detail']);
                $this->search();
            } else {
                if (auth()->user()->cannot('4.pnl-template-manager-edit')) {
                    throw new \Exception('You do not have permission to perform this action.');
                }

                if (empty($this->detail['id'])) {
                    throw new \Exception("Something went wrong. Please refresh the page and try again.");
                }

                $pnl_template_manager_old = \Devzone\Ams\Models\PnlTemplateManager::find($this->detail['id']);
                if (empty($pnl_template_manager_old)) {
                    throw new \Exception("Pnl Template Manager not found.");
                }
                \Devzone\Ams\Models\PnlTemplateManager::where('id', $this->detail['id'])->update($this->detail);
                $this->success = 'PnL Template Manager has been updated!!!';
                $this->reset('detail');
                $this->dispatchBrowserEvent('reset-select-2');
                $this->search();
            }
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }
    }

    public function edit($id): void
    {
        try {
            $this->detail = \Devzone\Ams\Models\PnlTemplateManager::select('id', 'report_name', 'income_accounts', 'expense_accounts')->find($id)->toArray();
            if (empty($this->detail)) {
                throw new \Exception('No record found!');
            }

            $fetch_income_accounts = ChartOfAccount::whereIn('id', $this->detail['income_accounts'])->select('id','name')->get()->toArray();
            $fetch_expense_accounts = ChartOfAccount::whereIn('id', $this->detail['expense_accounts'])->select('id', 'name')->get()->toArray();
            $this->is_edit = true;
            $this->dispatchBrowserEvent('edit', ['detail' => $this->detail, 'fetch_income_accounts' => $fetch_income_accounts, 'fetch_expense_accounts' => $fetch_expense_accounts]);;
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }
    }
    public function closeDeleteModal(){
        $this->delete_modal = false;
    }

    public function confirmDelete($id): void
    {
        $this->delete_id = $id;
        $this->delete_modal = true;
    }

    public function delete(): void
    {
        try {
            if (!auth()->user()->can('4.pnl-template-manager-delete')) {
                throw new \Exception('You do not have permission to perform this action.');
            }

            $found = \Devzone\Ams\Models\PnlTemplateManager::find($this->delete_id);
            if (empty($found)) {
                throw new \Exception('No record found!');
            }
            $found->delete();
            $this->success = 'Template Deleted Successfully';
            $this->search();
        } catch (\Exception $e) {
            $this->delete_modal = false;
            $this->addError('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('ams::livewire.reports.pnl-template-manager');
    }
}