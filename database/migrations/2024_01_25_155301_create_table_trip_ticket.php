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
        Schema::create('trip_ticket', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('ticket_no')->nullable();
            $table->string('transaction_slug')->nullable();
            $table->date('date')->nullable();
            $table->string('driver')->nullable();
            $table->string('vehicle')->nullable();
            $table->string('destination')->nullable();
            $table->string('purpose')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('approved_by_designation')->nullable();
            $table->string('certified_by')->nullable();
            $table->string('certified_by_designation')->nullable();
            $table->dateTime('departure')->nullable();
            $table->dateTime('return')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
            $table->string('gas_balance')->nullable();
            $table->string('gas_issued')->nullable();
            $table->string('purchased')->nullable();
            $table->string('total')->nullable();
            $table->string('consumed')->nullable();
            $table->string('gas_remaining_balance')->nullable();
            $table->string('odometer_from')->nullable();
            $table->string('odometer_to')->nullable();
            $table->string('distance_traveled')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_ticket');
    }
};
