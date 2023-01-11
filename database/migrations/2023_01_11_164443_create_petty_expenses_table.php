<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePettyExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petty_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('invoice_date');
            $table->string('name', 100);
            $table->string('contact_no', 20);
            $table->string('attachment')->nullable();
            $table->integer('account_head_id');
            $table->decimal('amount');
            $table->text('description')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('claimed_by')->nullable();
            $table->date('claimed_at')->nullable();
            $table->integer('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->integer('voucher_no')->nullable();
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
        Schema::dropIfExists('petty_expenses');
    }
}
