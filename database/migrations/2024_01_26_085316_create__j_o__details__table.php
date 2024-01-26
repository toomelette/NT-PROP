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
        Schema::create('jo_details', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('order_slug')->nullable();
            $table->string('jr_number')->nullable();
            $table->decimal('abc',20,2)->nullable();
            $table->string('resp_center')->nullable();
            $table->string('pap_code')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
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
        Schema::dropIfExists('jo_details');
    }
};
