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
        Schema::table('inventory_ppe', function (Blueprint $table) {
            $table->string('ppe_model')->nullable();
            $table->string('ppe_serial_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_ppe', function (Blueprint $table) {
            $table->dropColumn(['ppe_model','ppe_serial_no']);
        });
    }
};
