<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ams_customers', function (Blueprint $table) {
            $table->string('customer_code', 20)->nullable()->after('id');
            $table->string('frequency', 50)->nullable();
            $table->integer('grace_period')->default(0);
            $table->decimal('amount', 14, 2)->default(0.00)->comment('Credit Limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ams_customers', function (Blueprint $table) {
            $table->dropColumn(['customer_code', 'frequency', 'grace_period', 'amount']);
        });
    }
};
