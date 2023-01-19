<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInDayClosingTableAttachmentAndDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('day_closing', function (Blueprint $table) {
            $table->string('attachment')->after('date')->nullable();
            $table->text('description')->after('attachment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('day_closing', function (Blueprint $table) {
            $table->dropColumn('description','attachment');
        });
    }
}
