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
        Schema::create('po_details', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('order_slug')->nullable();
            $table->string('pr_number')->nullable();
            $table->decimal('abc',20,2)->nullable();
            $table->string('resp_center')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('po_details');
    }
};
