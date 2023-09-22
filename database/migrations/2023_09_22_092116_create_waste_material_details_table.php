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
        Schema::create('waste_material_details', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('waste_material_slug')->nullable();
            $table->integer('qty')->nullable();
            $table->string('unit')->nullable();
            $table->string('item')->nullable();
            $table->string('description')->nullable();
            $table->string('or_no')->nullable();
            $table->decimal('amount',20,2)->nullable();
            $table->decimal('total',20,2)->nullable();
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
        Schema::dropIfExists('waste_material_details');
    }
};
