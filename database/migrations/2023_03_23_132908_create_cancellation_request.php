<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancellationRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancellation_request', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('reason')->nullable();
            $table->string('ref_book')->nullable();
            $table->string('ref_number')->nullable();
            $table->date('ref_date')->nullable();
            $table->decimal('total_amount',20,2)->nullable();
            $table->string('requester')->nullable();
            $table->timestamps();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cancellation_request');
    }
}
