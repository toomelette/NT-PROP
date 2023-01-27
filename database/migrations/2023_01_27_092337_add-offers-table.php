<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aq_quotations',function (Blueprint $table){
            $table->id();
            $table->string('slug')->nullable();
            $table->string('aq_slug')->nullable();
            $table->string('supplier_slug')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('warranty')->nullable();
            $table->string('price_validity')->nullable();
            $table->string('delivery_term')->nullable();
            $table->string('payment_term')->nullable();
            $table->integer('has_attachments')->nullable();
        });
        Schema::create('aq_offer_details',function (Blueprint $table){
            $table->id();
            $table->string('slug')->nullable();
            $table->string('quotation_slug')->nullable();
            $table->string('item_slug')->nullable();
            $table->decimal('amount',20,2)->nullable();
            $table->longText('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aq_quotations');
        Schema::dropIfExists('aq_offer_details');
    }
}
