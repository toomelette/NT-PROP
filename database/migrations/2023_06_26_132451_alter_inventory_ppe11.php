<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInventoryPpe11 extends Migration
{
    public function up()
    {
        Schema::table('inventory_ppe', function (Blueprint $table) {
            $table->string('sub_major_account_group')->nullable();
            $table->string('general_ledger_account')->nullable();
            $table->string('fund_cluster')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
