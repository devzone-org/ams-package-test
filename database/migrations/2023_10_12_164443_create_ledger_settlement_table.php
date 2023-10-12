<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerSettlementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_settlements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ledger_id');
            $table->decimal('amount', 14)->default(0.00);
            $table->enum('status', ['t', 'f'])->default('f');
            $table->string('voucher_no', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledger_settlements');
    }
}
