<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->unsignedInteger('acc_vendor_id');
            $table->string('invoice_no', 100)->nullable();
            $table->decimal('amount', 11, 2);
            $table->unsignedInteger('expense_account_id');
            $table->text('description')->nullable();
            $table->unsignedInteger('requisite_by')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['unclaimed', 'claim-in-progress', 'claimed'])->default('unclaimed');
            $table->string('code', 20)->nullable()->index();
            $table->dateTime('claim_in_progress_at')->nullable();
            $table->unsignedInteger('claim_in_progress_by')->nullable();
            $table->dateTime('claimed_at')->nullable();
            $table->unsignedInteger('claimed_by')->nullable();
            $table->unsignedInteger('added_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('admin_expenses');
    }
}
