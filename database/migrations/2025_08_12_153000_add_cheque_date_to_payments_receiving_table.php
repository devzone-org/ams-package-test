<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments_receiving', function (Blueprint $table) {
            $table->date('approval_date')->nullable()->after('posting_date');
        });
    }

    public function down(): void
    {
        Schema::table('payments_receiving', function (Blueprint $table) {
            $table->dropColumn('approval_date');
        });
    }
};
