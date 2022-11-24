<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRespCodesTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resp_codes', function (Blueprint $table) {
            $table->string('department')->nullable();
            $table->string('division')->nullable();
            $table->string('section')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resp_codes', function (Blueprint $table) {
            $table->dropColumn(['department','division','section']);
        });
    }
}
