<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('supplier')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_tin')->nullable();
            $table->string('place_of_delivery')->nullable();
            $table->string('delivery_term')->nullable();
            $table->string('payment_term')->nullable();
            $table->decimal('total_gross',20,2)->nullable();
            $table->decimal('total',20,2)->nullable();
            $table->string('total_in_words')->nullable();
            $table->string('mode')->nullable();
            $table->decimal('supplier_representative')->nullable();
            $table->date('date_received')->nullable();
            $table->string('authorized_official')->nullable();
            $table->string('authorized_official_designation')->nullable();
            $table->string('funds_available')->nullable();
            $table->string('funds_available_designation')->nullable();
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
        Schema::dropIfExists('order');
    }
}
