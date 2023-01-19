<?php


namespace Devzone\Ams\Console;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class DumpMasterDataPermissions extends Command
{
    protected $signature = 'ams:master-data-permissions';

    protected $description = 'Dumping master data permissions for ams';

    public function handle()
    {
        $this->info('Dumping Master Data Permissions...');
        Permission::updateOrCreate(['name' => '2.dashboard'], ['guard_name' => 'web', 'description' => 'dashboard', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.coa.view'], ['guard_name' => 'web', 'description' => 'view chart of accounts ', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.edit.coa.status'], ['guard_name' => 'web', 'description' => 'active / inactive account status', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.create.coa.all'], ['guard_name' => 'web', 'description' => 'create account all levels', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.create.coa.level5'], ['guard_name' => 'web', 'description' => 'create account 5th level', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.create.transfer.restricted-date'], ['guard_name' => 'web', 'description' => 'create journal entry restricted date', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.create.transfer.any-date'], ['guard_name' => 'web', 'description' => 'create journal entry any date', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.delete.transfer.unapproved'], ['guard_name' => 'web', 'description' => 'delete transfer entry before approval', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.edit.transfer.unapproved'], ['guard_name' => 'web', 'description' => 'edit transfer entry before approval', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.post.unapprove'], ['guard_name' => 'web', 'description' => 'post un approve journals', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.view.ledger'], ['guard_name' => 'web', 'description' => 'view ledger', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.day.closing'], ['guard_name' => 'web', 'description' => 'day closing', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.payments.any'], ['guard_name' => 'web', 'description' => 'create payment and receiving transaction - any till', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.payments.own'], ['guard_name' => 'web', 'description' => 'create payment and receiving transaction - own till', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.payments.approve'], ['guard_name' => 'web', 'description' => 'payment and receiving transaction - approve ', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.payments.edit'], ['guard_name' => 'web', 'description' => 'payment and receiving transaction - edit ', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.payments.view'], ['guard_name' => 'web', 'description' => 'payment and receiving transaction - view ', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '2.payments.reversal'], ['guard_name' => 'web', 'description' => 'payment and receiving transaction - reversal ', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.trail-balance'], ['guard_name' => 'web', 'description' => 'Trail balance', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.pnl'], ['guard_name' => 'web', 'description' => 'Profit and Loss', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.balance-sheet'], ['guard_name' => 'web', 'description' => 'Statement of Financial Position', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.day-closing'], ['guard_name' => 'web', 'description' => 'Day Closing', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.trace-voucher'], ['guard_name' => 'web', 'description' => 'Trace Voucher', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.add.petty-expenses'], ['guard_name' => 'web', 'description' => 'Add Petty Expenses', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.edit.petty-expenses'], ['guard_name' => 'web', 'description' => 'Edit Petty Expenses', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.delete.petty-expenses'], ['guard_name' => 'web', 'description' => 'Delete Petty Expenses', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.claim.petty-expenses'], ['guard_name' => 'web', 'description' => 'Claim Petty Expenses', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.approve.petty-expenses'], ['guard_name' => 'web', 'description' => 'Approve Petty Expenses', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.reject.petty-expenses'], ['guard_name' => 'web', 'description' => 'Reject Petty Expenses', 'portal' => 'accounts', 'section' => 'accounts']);
        Permission::updateOrCreate(['name' => '3.view.petty-expenses'], ['guard_name' => 'web', 'description' => 'View Petty Expenses', 'portal' => 'accounts', 'section' => 'accounts']);


        $this->info('Dumping Permissions Finished...');
    }
}
