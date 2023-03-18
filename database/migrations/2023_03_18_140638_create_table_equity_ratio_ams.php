<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEquityRatioAms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equity_ratio', function (Blueprint $table) {
            $table->id();
            $table->string('partner_name');
            $table->decimal('ratio', 4, 2);
            $table->integer('account_id');
            $table->integer('drawing_account_id');
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
        Schema::dropIfExists('equity_ratio');
    }
}
