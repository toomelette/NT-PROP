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

        Schema::dropIfExists('gate_pass_details')
        Schema::create('gate_pass_details', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('gate_pass_slug')->nullable();
            $table->string('description')->nullable();
            $table->integer('qty')->nullable();
            $table->string('deleted_at')->nullable();
            $table->string('transaction_slug')->nullable();
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
        Schema::dropIfExists('gate_pass_details');
    }
};
