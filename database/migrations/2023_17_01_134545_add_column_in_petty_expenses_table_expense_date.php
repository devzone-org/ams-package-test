<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInPettyExpensesTableExpenseDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('petty_expenses', function (Blueprint $table) {
            $table->date('expense_date')->after('invoice_date')->nullable();
            $table->integer('reject_by')->after('description')->nullable();
            $table->text('reject_reason')->after('reject_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('petty_expenses', function (Blueprint $table) {
            $table->dropColumn('expense_date', 'reject_reason', 'reject_by');
        });
    }
}
