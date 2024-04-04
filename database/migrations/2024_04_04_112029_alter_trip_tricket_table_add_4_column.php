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
            $table->string('gear_oil')->nullable();
            $table->string('lubricant_oil')->nullable();
            $table->string('grease')->nullable();
            $table->string('remarks')->nullable();
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
            $table->dropColumn('gear_oil');
            $table->dropColumn('lubricant_oil');
            $table->dropColumn('grease');
            $table->dropColumn('remarks');
        });
    }
};
