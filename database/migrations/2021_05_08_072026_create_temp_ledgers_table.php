<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_ledgers', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id')->nullable();
            $table->integer('voucher_no');
            $table->string('tracking_id',50)->nullable();
            $table->string('type',50)->nullable();
            $table->decimal('debit',11)->default(0);
            $table->decimal('credit',11)->default(0);
            $table->text('description')->nullable();
            $table->date('posting_date');
            $table->integer('posted_by');
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
        Schema::dropIfExists('temp_ledgers');
    }
}
