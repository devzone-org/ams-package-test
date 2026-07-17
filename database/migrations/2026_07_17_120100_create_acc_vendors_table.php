<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('business_name', 100);
            $table->string('business_address', 255)->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->string('owner_name', 100)->nullable();
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
        Schema::dropIfExists('acc_vendors');
    }
}
