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
            $table->string('condition')->nullable();
            $table->string('serial_no')->nullable();
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
            $table->dropColumn('condition');
            $table->dropColumn('serial_no');
        });
    }
};
