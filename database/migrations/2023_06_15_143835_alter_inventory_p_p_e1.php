<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInventoryPPE1 extends Migration
{
    public function up()
    {
        Schema::table('inventory_ppe', function (Blueprint $table) {
            $table->string('par_code')->nullable();
        });
    }

    public function down()
    {
        Schema::table('inventory_ppe', function (Blueprint $table) {
            $table->string('par_code')->nullable();
        });
    }
}
