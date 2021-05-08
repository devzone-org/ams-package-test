<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->integer('voucher_no');
            $table->string('tracking_id',50)->nullable();
            $table->string('type',50)->nullable();
            $table->decimal('debit',11)->default(0);
            $table->decimal('credit',11)->default(0);
            $table->text('description');
            $table->date('posting_date');
            $table->integer('posted_by');
            $table->datetime('approved_at');
            $table->integer('approved_by');
            $table->char('is_approve',1)->default('f');
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
        Schema::dropIfExists('ledgers');
    }
}
