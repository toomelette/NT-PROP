<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('transaction_slug');
            $table->string('stock_no')->nullable();
            $table->string('unit')->nullable();
            $table->string('item')->nullable();
            $table->string('description')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('unit_cost',20,2)->nullable();
            $table->decimal('total_cost',20,2)->nullable();
            $table->string('property_no')->nullable();
            $table->string('nature_of_work')->nullable();
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
        Schema::dropIfExists('transaction_details');
    }
}
