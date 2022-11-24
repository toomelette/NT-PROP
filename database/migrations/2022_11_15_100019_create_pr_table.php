<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('department')->nullable();
            $table->string('division')->nullable();
            $table->string('respCenter')->nullable();
            $table->string('prNo')->nullable();
            $table->date('prDate')->nullable();
            $table->string('sai')->nullable();
            $table->date('saiDate')->nullable();
            $table->text('purpose')->nullable();
            $table->string('requestedBy')->nullable();
            $table->string('requestedByDesignation')->nullable();
            $table->string('approvedBy')->nullable();
            $table->string('approvedByDesignation')->nullable();
            $table->timestamps();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
        });
        Schema::create('pr_items',function (Blueprint $table){
            $table->string('slug')->nullable();
            $table->string('pr_slug')->nullable();
            $table->string('stockNo')->nullable();
            $table->string('unit')->nullable();
            $table->string('item')->nullable();
            $table->text('description')->nullable();
            $table->decimal('qty',10,2)->nullable();
            $table->decimal('unitCost',20,2)->nullable();
            $table->decimal('totalCost',20,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr');
        Schema::dropIfExists('pr_items');
    }
}
