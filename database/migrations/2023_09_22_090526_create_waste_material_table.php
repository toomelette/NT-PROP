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
        Schema::create('waste_material', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('wm_number')->nullable();
            $table->string('storage')->nullable();
            $table->date('date')->nullable();
            $table->string('taken_from')->nullable();
            $table->string('taken_through')->nullable();
            $table->string('certified_by')->nullable();
            $table->string('certified_by_designation')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('approved_by_designation')->nullable();
            $table->string('witnessed_by')->nullable();
            $table->string('witnessed_by_designation')->nullable();
            $table->string('inspected_by')->nullable();
            $table->string('inspected_by_designation')->nullable();

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
        Schema::dropIfExists('waste_material');
    }
};
