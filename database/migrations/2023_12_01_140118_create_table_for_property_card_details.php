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
        Schema::create('property_card_details', function (Blueprint $table) {
            $table->id();
            $table->string('property_card_slug')->nullable();
            $table->date('date')->nullable();
            $table->string('ref_no')->nullable();
            $table->integer('receipt_qty')->nullable();
            $table->integer('qty')->nullable();
            $table->string('purpose')->nullable();
            $table->integer('bal_qty')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('remarks')->nullable();
            $table->string('deleted_at')->nullable();
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
        Schema::dropIfExists('property_card_details');
    }
};
