<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPpmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ppmp');
        Schema::create('ppmp', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('ppmpCode')->nullable();
            $table->string('papCode')->nullable();
            $table->string('sourceOfFund')->nullable();
            $table->string('stockNo')->nullable();
            $table->string('budgetType')->nullable();
            $table->string('modeOfProc')->nullable();
            $table->decimal('unitCost',20,2)->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('estTotalCost',20,2)->nullable();
            $table->string('remarks')->nullable();
            $table->integer('qty_jan')->nullable();
            $table->integer('qty_feb')->nullable();
            $table->integer('qty_mar')->nullable();
            $table->integer('qty_apr')->nullable();
            $table->integer('qty_may')->nullable();
            $table->integer('qty_jun')->nullable();
            $table->integer('qty_jul')->nullable();
            $table->integer('qty_aug')->nullable();
            $table->integer('qty_sep')->nullable();
            $table->integer('qty_oct')->nullable();
            $table->integer('qty_nov')->nullable();
            $table->integer('qty_dec')->nullable();
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
        Schema::dropIfExists('ppmp');
    }
}
