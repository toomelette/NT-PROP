<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransactionTable1 extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('supplier')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_tin')->nullable();
            $table->string('place_of_delivery')->nullable();
            $table->string('delivery_term')->nullable();
            $table->string('payment_term')->nullable();
            $table->decimal('total_gross',20,2)->nullable();
            $table->decimal('total',20,2)->nullable();
            $table->string('mode')->nullable();
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('supplier');
            $table->dropColumn('supplier_address');
            $table->dropColumn('supplier_tin');
            $table->dropColumn('place_of_delivery');
            $table->dropColumn('delivery_term');
            $table->dropColumn('payment_term');
            $table->dropColumn('total_gross');
            $table->dropColumn('total');
            $table->dropColumn('mode');
        });
    }
}
