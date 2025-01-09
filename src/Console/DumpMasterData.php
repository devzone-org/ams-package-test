<?php


namespace Devzone\Ams\Console;


use Devzone\Ams\Models\ChartOfAccount;
use Devzone\Ams\Models\Voucher;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class DumpMasterData extends Command
{
    protected $signature = 'ams:master-data';

    protected $description = 'Dumping master data for ams';

    public function handle()
    {
        $this->info('Dumping Master Data...');
        if (!Voucher::where('name', 'temp_voucher')->exists()) {
            Voucher::create([
                'name' => 'temp_voucher'
            ]);
        }
        if (!Voucher::where('name', 'voucher')->exists()) {
            Voucher::create([
                'name' => 'voucher'
            ]);
        }

        if (!Voucher::where('name', 'pv_no')->exists()) {
            Voucher::create([
                'name' => 'pv_no'
            ]);
        }

        if (!Voucher::where('name', 'coa')->exists()) {
            Voucher::create([
                'name' => 'coa'
            ]);
        }

        if (!Voucher::where('name', 'advances_receipt')->exists()) {
            Voucher::create([
                'name' => 'advances_receipt'
            ]);
        }
        $this->info('Dumping Vouchers Finished...');

        ChartOfAccount::updateOrCreate(['id' => '1'], ['name' => 'Assets', 'type' => 'Assets', 'sub_account' => null, 'level' => '1', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '2'], ['name' => 'Fixed Assets', 'type' => 'Assets', 'sub_account' => '1', 'level' => '2', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '3'], ['name' => 'Fixed Assets', 'type' => 'Assets', 'sub_account' => '2', 'level' => '3', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '4'], ['name' => 'Property, Plant & Equipment', 'type' => 'Assets', 'sub_account' => '3', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '5'], ['name' => 'Accumulated Depreciation', 'type' => 'Assets', 'sub_account' => '3', 'level' => '4', 'nature' => 'd', 'is_contra' => 't']);
        ChartOfAccount::updateOrCreate(['id' => '6'], ['name' => 'Non-Current Assets', 'type' => 'Assets', 'sub_account' => '1', 'level' => '2', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '7'], ['name' => 'Long Term Advances, Deposits & Prepayments', 'type' => 'Assets', 'sub_account' => '6', 'level' => '3', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '8'], ['name' => 'Long Term Security Deposits', 'type' => 'Assets', 'sub_account' => '7', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '9'], ['name' => 'Current Assets', 'type' => 'Assets', 'sub_account' => '1', 'level' => '2', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '10'], ['name' => 'Cash and Cash Equivalents', 'type' => 'Assets', 'sub_account' => '9', 'level' => '3', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '11'], ['name' => 'Cash at Banks', 'reference' => 'cash-at-banks-4', 'type' => 'Assets', 'sub_account' => '10', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '12'], ['name' => 'Cash in Hand', 'reference' => 'cash-in-hand-4', 'type' => 'Assets', 'sub_account' => '10', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '13'], ['name' => 'Advances, Deposits and Prepayments', 'type' => 'Assets', 'sub_account' => '9', 'level' => '3', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '14'], ['name' => 'Advances', 'type' => 'Assets', 'sub_account' => '13', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '15'], ['name' => 'Staff Loan', 'type' => 'Assets', 'sub_account' => '13', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '16'], ['name' => 'Prepayments', 'type' => 'Assets', 'sub_account' => '13', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '17'], ['name' => 'Short Term Security Deposits', 'type' => 'Assets', 'sub_account' => '13', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '18'], ['name' => 'Other Receivables', 'type' => 'Assets', 'sub_account' => '13', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '19'], ['name' => 'Liabilities', 'type' => 'Liabilities', 'sub_account' => null, 'level' => '1', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '20'], ['name' => 'Long term liabilities', 'type' => 'Liabilities', 'sub_account' => '19', 'level' => '2', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '21'], ['name' => 'Long term liabilities', 'type' => 'Liabilities', 'sub_account' => '20', 'level' => '3', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '22'], ['name' => 'Long term liabilities', 'type' => 'Liabilities', 'sub_account' => '21', 'level' => '4', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '23'], ['name' => 'Current Liabilities', 'type' => 'Liabilities', 'sub_account' => '19', 'level' => '2', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '24'], ['name' => 'Accrued Liabilities and Other Liabilities', 'type' => 'Liabilities', 'sub_account' => '23', 'level' => '3', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '25'], ['name' => 'Wages payable, rent, tax and utilities', 'type' => 'Liabilities', 'sub_account' => '24', 'level' => '4', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '26'], ['name' => 'Accrued Expenses', 'type' => 'Liabilities', 'sub_account' => '24', 'level' => '4', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '27'], ['name' => 'Short-term Debt', 'type' => 'Liabilities', 'sub_account' => '24', 'level' => '4', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '28'], ['name' => 'Other Payable', 'type' => 'Liabilities', 'sub_account' => '24', 'level' => '4', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '29'], ['name' => 'Equity', 'type' => 'Equity', 'sub_account' => null, 'level' => '1', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '30'], ['name' => 'Partners Capital', 'type' => 'Equity', 'sub_account' => '29', 'level' => '2', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '31'], ['name' => 'Partners Capital', 'type' => 'Equity', 'sub_account' => '30', 'level' => '3', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '32'], ['name' => 'Partners Capital', 'type' => 'Equity', 'sub_account' => '31', 'level' => '4', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '33'], ['name' => 'Income', 'type' => 'Income', 'sub_account' => null, 'level' => '1', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '34'], ['name' => 'Revenue', 'type' => 'Income', 'sub_account' => '33', 'level' => '2', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '35'], ['name' => 'Operating Revenue', 'type' => 'Income', 'sub_account' => '34', 'level' => '3', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '36'], ['name' => 'Income', 'type' => 'Income', 'sub_account' => '35', 'level' => '4', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '38'], ['name' => 'Other Operating Income', 'type' => 'Income', 'sub_account' => '34', 'level' => '3', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '41'], ['name' => 'Expenses', 'type' => 'Expenses', 'sub_account' => null, 'level' => '1', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '42'], ['name' => 'Expenses', 'type' => 'Expenses', 'sub_account' => '41', 'level' => '2', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '43'], ['name' => 'Administrative Expenses', 'type' => 'Expenses', 'sub_account' => '42', 'level' => '3', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '44'], ['name' => 'Salaries, Wages and other Benefits', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '45'], ['name' => 'Hotels, travel and subsistence', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '46'], ['name' => 'Legal and professional', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '47'], ['name' => 'Bank Commission and Charges', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '48'], ['name' => 'Marketing and promotion Expense', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '49'], ['name' => 'Rent, Rates and Taxes', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '50'], ['name' => 'Leasing, Insurance and motor expenses', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '51'], ['name' => 'Telephone and Mobile', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '52'], ['name' => 'Light and Heat', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '53'], ['name' => 'Printing, Stationary and Postage', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '54'], ['name' => 'Repairs and Maintenance', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '55'], ['name' => 'Business Events', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '56'], ['name' => 'Other Admin Expenses', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '57'], ['name' => 'Vendor Payable', 'type' => 'Liabilities', 'sub_account' => '23', 'level' => '3', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '58'], ['name' => 'Vendor Payable', 'type' => 'Liabilities', 'sub_account' => '57', 'level' => '4', 'nature' => 'c', 'reference' => 'vendor-payable-4']);
        ChartOfAccount::updateOrCreate(['id' => '59'], ['name' => 'Inventory', 'reference' => 'inventory-4', 'type' => 'Assets', 'sub_account' => '10', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '61'], ['name' => 'Cost of Sales', 'reference' => 'cost-of-sales-4', 'type' => 'Expenses', 'sub_account' => '43', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '63'], ['name' => 'Employee Share Payable', 'type' => 'Liabilities', 'sub_account' => '23', 'level' => '3', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '64'], ['name' => 'Employee Share Payable', 'type' => 'Liabilities', 'sub_account' => '63', 'level' => '4', 'nature' => 'c', 'reference' => 'employee-share-payable-4']);
        ChartOfAccount::updateOrCreate(['id' => '66'], ['name' => 'Surplus Income', 'type' => 'Income', 'sub_account' => '38', 'level' => '4', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '75'], ['name' => 'Trade Payable', 'type' => 'Liabilities', 'sub_account' => '23', 'level' => '3', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '76'], ['name' => 'Trade Payable', 'type' => 'Liabilities', 'sub_account' => '75', 'level' => '4', 'nature' => 'c']);
        ChartOfAccount::updateOrCreate(['id' => '77'], ['name' => 'Cost of Sales', 'reference' => 'cost-of-sales-3', 'type' => 'Expenses', 'sub_account' => '42', 'level' => '3', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['id' => '78'], ['name' => 'Cost of Sales', 'reference' => 'cost-of-sales-4', 'type' => 'Expenses', 'sub_account' => '77', 'level' => '4', 'nature' => 'd']);

        ChartOfAccount::updateOrCreate(['id' => '79'], ['name' => 'Customers Receivable', 'type' => 'Assets', 'reference' => 'commission-receivable-4', 'sub_account' => '10', 'level' => '4', 'nature' => 'd']);
        ChartOfAccount::updateOrCreate(['reference' => 'customer-receivable-4'], ['name' => 'Customers Receivable', 'type' => 'Assets', 'sub_account' => '10', 'level' => '4', 'nature' => 'd']);

        $cash_suplus = ChartOfAccount::where('reference', 'inc-cash-surplus')->exists();
        if(!$cash_suplus){
            $voucher = \Devzone\Ams\Models\Voucher::where('name', 'coa')->get();
            $voucher = $voucher->first();
            $count = $voucher->value;
            $count = $count + 1;

            DB::table('vouchers')
                ->where('id', $voucher->id)
                ->update([
                    'value' => DB::raw('value + 1')
                ]);
            $code = $count;
            $code = str_pad($code, 7, "0", STR_PAD_LEFT);
            \Devzone\Ams\Models\ChartOfAccount::create([
                'name' => 'Income - Till Cash Surplus',
                'type' => 'Income',
                'sub_account' => '66',
                'level' => 5,
                'code' => $code,
                'nature' => 'c',
                'status' => 't',
                'reference' => 'inc-cash-surplus',
            ]);
        }

        $cash_shortage = ChartOfAccount::where('reference', 'exp-cash-shortage')->exists();
        if(!$cash_shortage){
            $voucher = \Devzone\Ams\Models\Voucher::where('name', 'coa')->get();
            $voucher = $voucher->first();
            $count = $voucher->value;
            $count = $count + 1;

            DB::table('vouchers')
                ->where('id', $voucher->id)
                ->update([
                    'value' => DB::raw('value + 1')
                ]);
            $code = $count;
            $code = str_pad($code, 7, "0", STR_PAD_LEFT);
            \Devzone\Ams\Models\ChartOfAccount::create([
                'name' => 'Expense Till Cash Shortage',
                'type' => 'Expenses',
                'sub_account' => '56',
                'level' => 5,
                'code' => $code,
                'nature' => 'd',
                'status' => 't',
                'reference' => 'exp-cash-shortage',
            ]);
        }

        if (env('IS_HOSPITAL', false)) {


            ChartOfAccount::updateOrCreate(['reference' => 'pharmacy-inventory-5'], ['name' => 'Pharmacy Inventory', 'type' => 'Assets', 'sub_account' => '59', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'income-opd-5'], ['name' => 'Income - OPD', 'type' => 'Income', 'sub_account' => '36', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'expense-commission-opd-5'], ['name' => 'Expense OPD Charges', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'inc-cash-surplus'], ['name' => 'Income - Till Cash Surplus', 'type' => 'Income', 'sub_account' => '66', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'cost-of-sales-pharmacy-5'], ['name' => 'Cost of Sales - Pharmacy', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'income-pharmacy-5'], ['name' => 'Sales - Pharmacy', 'type' => 'Income', 'sub_account' => '36', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'expense-ipd-ota-commission-5'], ['name' => 'Expense IPD OTA Charges', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'expense-ipd-doctor-commission-5'], ['name' => 'Expense IPD Doctor Charges', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'expense-ipd-nurse-commission-5'], ['name' => 'Expense IPD Nurse Charges', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'expense-ipd-medicine-commission-5'], ['name' => 'Expense Inter Transfer IPD Medicine', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'income-ipd-5'], ['name' => 'Income - IPD', 'type' => 'Income', 'sub_account' => '36', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'indoor-advances-5'], ['name' => 'Advances - IPD Admissions', 'type' => 'Liabilities', 'sub_account' => '76', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'cash-in-hand-muhammad-talha-5'], ['name' => 'Cash in Hand - Muhammad Talha', 'type' => 'Assets', 'sub_account' => '12', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'expense-ipd-anesthesia-commission-5'], ['name' => 'Expense IPD Anesthesia Charges', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'income-return-pharmacy-5'], ['name' => 'Sales return -Pharmacy', 'type' => 'Income', 'sub_account' => '36', 'level' => '5', 'nature' => 'c', 'is_contra' => 't']);
            ChartOfAccount::updateOrCreate(['reference' => 'payable-medicine-5'], ['name' => 'Payable Inter Transfer IPD Medicine', 'type' => 'Liabilities', 'sub_account' => '76', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'exp-cash-shortage'], ['name' => 'Expense Till Cash Shortage', 'type' => 'Expenses', 'sub_account' => '56', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'income-lab-5'], ['name' => 'Income - Lab', 'type' => 'Income', 'sub_account' => '36', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'income-radiology-5'], ['name' => 'Income - Radiology', 'type' => 'Income', 'sub_account' => '36', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'advance-tax-236'], ['name' => 'Recoverable Advance Tax u / s 236(H)', 'type' => 'Assets', 'sub_account' => '18', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'income-dayout-5'], ['name' => 'Income - Dayout', 'type' => 'Income', 'sub_account' => '36', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'income-return-dayout-5'], ['name' => 'Dayout return ', 'type' => 'Income', 'sub_account' => '36', 'level' => '5', 'nature' => 'c', 'is_contra' => 't']);
            ChartOfAccount::updateOrCreate(['reference' => 'expense-admission-referral-5'], ['name' => 'Expense Patient Referral Commission', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'expense-doctor-visits-5'], ['name' => 'Expense Doctor Visits', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'expense-lab-shares-5'], ['name' => 'Expense Lab Shares', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'expense-radio-shares-5'], ['name' => 'Expense Radiology Shares', 'type' => 'Expenses', 'sub_account' => '61', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'exp-invoice-rounding-off'], ['name' => 'Expense Invoice Rounding Off', 'type' => 'Expenses', 'sub_account' => '56', 'level' => '5', 'nature' => 'd']);
        }

        if (env('IS_DISTRIBUTION_POS', false)) {
            ChartOfAccount::updateOrCreate(['reference' => 'inventory-5'], ['name' => 'Inventory', 'type' => 'Assets', 'sub_account' => '59', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'sales-tax-paid-on-purchase-5'], ['name' => 'Sales Tax Paid On Purchases', 'type' => 'Assets', 'sub_account' => '18', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'advance-tax-paid-on-purchase-5'], ['name' => 'Advance Tax Paid On Purchases', 'type' => 'Assets', 'sub_account' => '18', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'fed-paid-on-purchase-5'], ['name' => 'FED Paid On Purchases', 'type' => 'Assets', 'sub_account' => '18', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'income-discount-5'], ['name' => 'Income Discount', 'type' => 'Liabilities', 'sub_account' => '28', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'allowances-mfr-5'], ['name' => 'Allowances MFR', 'type' => 'Liabilities', 'sub_account' => '28', 'level' => '5', 'nature' => 'c']);


            ChartOfAccount::updateOrCreate(['reference' => 'cost-of-sales-5'], ['name' => 'Cost of Sales', 'type' => 'Expenses', 'sub_account' => '78', 'level' => '5', 'nature' => 'd']);
            ChartOfAccount::updateOrCreate(['reference' => 'expenses-disc-granted-5'], ['name' => 'Expense Discount Granted', 'type' => 'Expenses', 'sub_account' => '68', 'level' => '5', 'nature' => 'd']);

            ChartOfAccount::updateOrCreate(['reference' => 'advance-tax-received-on-sale-5'], ['name' => 'Advance Tax Received On Sale', 'type' => 'Liabilities', 'sub_account' => '25', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'sales-5'], ['name' => 'Sales', 'type' => 'Income', 'sub_account' => '36', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'income-commission-5'], ['name' => 'Income Commission', 'type' => 'Income', 'sub_account' => '36', 'level' => '5', 'nature' => 'c']);
            ChartOfAccount::updateOrCreate(['reference' => 'sale-tax-received-on-sale-5'], ['name' => 'Sales Tax Received On Sale', 'type' => 'Liabilities', 'sub_account' => '25', 'level' => '5', 'nature' => 'c']);
        }
        $this->info('Dumping Chart of Accounts Finished...');

 
    }
}
