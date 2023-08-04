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
        Schema::create('request_for_vehicle', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('request_no',10)->nullable();
            $table->string('name')->nullable();
            $table->string('rc')->nullable();
            $table->string('purpose',512)->nullable();
            $table->string('requested_by')->nullable();
            $table->string('requested_by_position')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('approved_by_position')->nullable();
            $table->timestamps();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
            $table->string('action',20)->nullable();
            $table->string('action_by',45)->nullable();
            $table->dateTime('action_at');
            $table->string('remarks',512);
        });

        Schema::create('request_for_vehicle_passengers', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('request_slug')->nullable();
            $table->string('name')->nullable();
        });

        Schema::create('request_for_vehicle_details', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('request_slug')->nullable();
            $table->dateTime('datetime')->nullable();
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
        Schema::dropIfExists('request_for_vehicle');
        Schema::dropIfExists('request_for_vehicle_passengers');
        Schema::dropIfExists('request_for_vehicle_details');
    }
};
