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
        Schema::table('cancellation_request', function (Blueprint $table) {
            $table->integer('project_id')->nullable();
        });

        Schema::table('inventory_ppe', function (Blueprint $table) {
            $table->integer('project_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cancellation_request', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });

        Schema::table('inventory_ppe', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
};
