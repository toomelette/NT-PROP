<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRfqToTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('rfq_no')->nullable();
            $table->date('rfq_deadline')->nullable();
            $table->string('rfq_s_name')->nullable();
            $table->string('rfq_s_position')->nullable();
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
            $table->dropColumn(['rfq_no','rfq_deadline','rfq_s_name','rfq_s_position']);
        });
    }
}
