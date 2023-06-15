<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInventoryPPE extends Migration
{
    public function up()
    {
        Schema::table('inventory_ppe', function (Blueprint $table) {
            $table->string('ref_book')->nullable();
        });
    }

    public function down()
    {
        Schema::table('inventory_ppe', function (Blueprint $table) {
            $table->string('ref_book')->nullable();
        });
    }
}
