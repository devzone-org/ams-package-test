<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddClaimInProgressToAdminExpenses extends Migration
{
    public function up()
    {
        // idempotent: fresh installs already get these from the create migration
        DB::statement("ALTER TABLE admin_expenses MODIFY COLUMN status ENUM('unclaimed','claim-in-progress','claimed') NOT NULL DEFAULT 'unclaimed'");

        if (Schema::hasColumn('admin_expenses', 'status_changed_at')) {
            DB::statement("ALTER TABLE admin_expenses CHANGE status_changed_at claimed_at DATETIME NULL");
        }
        if (Schema::hasColumn('admin_expenses', 'status_changed_by')) {
            DB::statement("ALTER TABLE admin_expenses CHANGE status_changed_by claimed_by INT UNSIGNED NULL");
        }

        Schema::table('admin_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_expenses', 'code')) {
                $table->string('code', 20)->nullable()->unique()->after('status');
            }
            if (!Schema::hasColumn('admin_expenses', 'claim_in_progress_at')) {
                $table->dateTime('claim_in_progress_at')->nullable()->after('code');
            }
            if (!Schema::hasColumn('admin_expenses', 'claim_in_progress_by')) {
                $table->unsignedInteger('claim_in_progress_by')->nullable()->after('claim_in_progress_at');
            }
        });
    }

    public function down()
    {
        Schema::table('admin_expenses', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn(['code', 'claim_in_progress_at', 'claim_in_progress_by']);
        });

        DB::statement("ALTER TABLE admin_expenses CHANGE claimed_at status_changed_at DATETIME NULL");
        DB::statement("ALTER TABLE admin_expenses CHANGE claimed_by status_changed_by INT UNSIGNED NULL");
        DB::statement("ALTER TABLE admin_expenses MODIFY COLUMN status ENUM('unclaimed','claimed') NOT NULL DEFAULT 'unclaimed'");
    }
}
