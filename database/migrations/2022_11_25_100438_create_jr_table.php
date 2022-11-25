<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jr', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('respCenter')->nullable();
            $table->string('papCode')->nullable();
            $table->string('jrNo')->nullable();
            $table->date('jrDate')->nullable();
            $table->string('purpose')->nullable();
            $table->string('certifiedBy')->nullable();
            $table->string('certifiedByDesignation')->nullable();
            $table->string('requestedBy')->nullable();
            $table->string('requestedByDesignation')->nullable();
            $table->string('approvedBy')->nullable();
            $table->string('approvedByDesignation')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('jr_items',function (Blueprint $table){
            $table->id();
            $table->string('slug')->nullable();
            $table->string('jr_slug')->nullable();
            $table->string('propertyNo')->nullable();
            $table->string('uom')->nullable();
            $table->string('item')->nullable();
            $table->string('description')->nullable();
            $table->integer('qty')->nullable();
            $table->string('natureOfWork')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jr');
        Schema::dropIfExists('jr_items');
    }
}
