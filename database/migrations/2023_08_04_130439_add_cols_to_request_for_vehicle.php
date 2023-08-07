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
        Schema::table('request_for_vehicle', function (Blueprint $table) {
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->string('destination',512)->nullable();
            $table->string('vehicle_assigned')->nullable();
            $table->string('driver_assigned')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_for_vehicle', function (Blueprint $table) {
            $table->dropColumn(['from','to','vehicle_assigned','driver_assigned']);
        });
    }
};
