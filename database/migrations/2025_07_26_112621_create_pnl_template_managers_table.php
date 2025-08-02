<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePnlTemplateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pnl_template_managers', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');
            $table->json('income_accounts');
            $table->json('expense_accounts');
            $table->unsignedInteger('added_by');
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
        Schema::dropIfExists('pnl_template_managers');
    }
}
