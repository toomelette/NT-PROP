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
            $table->integer('inv_taken')->nullable();
            $table->date('inv_date')->nullable();
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
            $table->dropColumn('inv_taken');
            $table->dropColumn('inv_date');
        });
    }
};
