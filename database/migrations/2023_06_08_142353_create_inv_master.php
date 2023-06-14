<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_ppe', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('propuniqueno')->nullable();
            $table->string('article')->nullable();
            $table->string('description')->nullable();
            $table->string('propertyno')->nullable();
            $table->string('uom')->nullable();
            $table->decimal('acquiredcost',20,2)->nullable();
            $table->integer('qtypercard')->nullable();
            $table->integer('onhandqty')->nullable();
            $table->integer('shortqty')->nullable();
            $table->integer('shortvalue')->nullable();
            $table->date('dateacquired')->nullable();
            $table->string('remarks')->nullable();
            $table->string('acctemployee_no')->nullable();
            $table->string('acctemployee_fname')->nullable();
            $table->string('acctemployee_post')->nullable();
            $table->string('respcenter')->nullable();
            $table->string('supplier')->nullable();
            $table->string('invoiceno')->nullable();
            $table->date('invoicedate')->nullable();
            $table->string('pono')->nullable();
            $table->date('podate')->nullable();
            $table->string('invtacctcode')->nullable();
            $table->string('location')->nullable();
            $table->string('acquiredmode')->nullable();
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
        Schema::dropIfExists('inventory');
    }
}
