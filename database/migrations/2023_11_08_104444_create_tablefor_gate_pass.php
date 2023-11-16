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
        Schema::create('gp', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('gp_number')->nullable();
            $table->date('date')->nullable();
            $table->string('bearer')->nullable();
            $table->string('originated_from')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('guard_on_duty')->nullable();
            $table->string('received_by')->nullable();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
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
        Schema::dropIfExists('gp');
    }
};
