<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddClaimInProgressToAdminExpenses extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE admin_expenses MODIFY COLUMN status ENUM('unclaimed','claim-in-progress','claimed') NOT NULL DEFAULT 'unclaimed'");

        Schema::table('admin_expenses', function (Blueprint $table) {
            $table->string('code', 20)->nullable()->unique()->after('status');
        });
    }

    public function down()
    {
        Schema::table('admin_expenses', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });

        DB::statement("ALTER TABLE admin_expenses MODIFY COLUMN status ENUM('unclaimed','claimed') NOT NULL DEFAULT 'unclaimed'");
    }
}
