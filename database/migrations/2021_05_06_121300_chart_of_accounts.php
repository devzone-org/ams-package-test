<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChartOfAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('type',20);
            $table->integer('sub_account')->nullable();
            $table->char('level',1);
            $table->string('code',7)->nullable();
            $table->char('nature',1);
            $table->char('status',1)->default('t');
            $table->char('is_contra',1)->default('f');
            $table->string('reference',50)->nullable();
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
        Schema::dropIfExists('chart_of_accounts');
    }
}
