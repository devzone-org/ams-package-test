<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayClosingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_closing', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->string('ref_1')->nullable();
            $table->decimal('ref_amount_1')->nullable();
            $table->string('ref_2')->nullable();
            $table->decimal('ref_amount_2')->nullable();
            $table->string('ref_3')->nullable();
            $table->decimal('ref_amount_3')->nullable();
            $table->string('ref_4')->nullable();
            $table->decimal('ref_amount_4')->nullable();
            $table->string('ref_5')->nullable();
            $table->decimal('ref_amount_5')->nullable();
            $table->string('ref_6')->nullable();
            $table->decimal('ref_amount_6')->nullable();
            $table->string('ref_7')->nullable();
            $table->decimal('ref_amount_7')->nullable();
            $table->string('ref_8')->nullable();
            $table->decimal('ref_amount_8')->nullable();
            $table->string('ref_9')->nullable();
            $table->decimal('ref_amount_9')->nullable();
            $table->string('ref_10')->nullable();
            $table->decimal('ref_amount_10')->nullable();
            $table->string('ref_11')->nullable();
            $table->decimal('ref_amount_11')->nullable();
            $table->string('ref_12')->nullable();
            $table->decimal('ref_amount_12')->nullable();
            $table->string('ref_13')->nullable();
            $table->decimal('ref_amount_13')->nullable();
            $table->string('ref_14')->nullable();
            $table->decimal('ref_amount_14')->nullable();
            $table->string('ref_15')->nullable();
            $table->decimal('ref_amount_15')->nullable();
            $table->integer('close_by');
            $table->decimal('closing_balance');
            $table->decimal('physical_cash');
            $table->decimal('cash_retained');
            $table->integer('voucher_no');
            $table->integer('transfer_to');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_closing');
    }
}
