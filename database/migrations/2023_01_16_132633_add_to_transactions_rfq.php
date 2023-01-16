<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToTransactionsRfq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('rfq_user_created')->nullable();
            $table->dateTime('rfq_created_at')->nullable();
            $table->string('rfq_user_updated')->nullable();
            $table->dateTime('rfq_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['rfq_user_created','rfq_created_at','rfq_user_updated','rfq_updated_at']);
        });
    }
}
