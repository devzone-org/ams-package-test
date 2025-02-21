<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmsCustomerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ams_customer_payments', function (Blueprint $table) {
            $table->id();
            $table->string('month');
            $table->string('voucher_no');
            $table->enum('temp_voucher', ['t', 'f'])->default('t');
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
        Schema::dropIfExists('ams_customer_payments');
    }
}
