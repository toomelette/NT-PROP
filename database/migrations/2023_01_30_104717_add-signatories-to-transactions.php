<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignatoriesToTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions',function (Blueprint $table){
            $table->string('prepared_by')->nullable();
            $table->string('prepared_by_position')->nullable();
            $table->string('noted_by')->nullable();
            $table->string('noted_by_position')->nullable();
            $table->string('recommending_approval')->nullable();
            $table->string('recommending_approval_position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions',function (Blueprint $table){
            $table->dropColumn(['prepared_by','prepared_by_position','noted_by','noted_by_position','recommending_approval','recommending_approval_position',]);
        });
    }
}
