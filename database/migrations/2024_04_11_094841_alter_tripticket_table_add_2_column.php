<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_ticket', function (Blueprint $table) {
            $table->dateTime('arrival')->nullable();
            $table->dateTime('return_departure')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_ticket', function (Blueprint $table) {
            $table->dropColumn('arrival');
            $table->dropColumn('return_departure');
        });
    }
};
