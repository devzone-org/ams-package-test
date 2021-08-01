<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsReceivingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_receiving', function (Blueprint $table) {
            $table->id();
            $table->string('nature',20);
            $table->date('posting_date');
            $table->integer('first_account_id');
            $table->integer('second_account_id');
            $table->decimal('amount');
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->string('mode',20);
            $table->string('instrument_no',30)->nullable();
            $table->integer('added_by');
            $table->integer('voucher_no')->nullable();
            $table->integer('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
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
        Schema::dropIfExists('payments_receiving');
    }
}
