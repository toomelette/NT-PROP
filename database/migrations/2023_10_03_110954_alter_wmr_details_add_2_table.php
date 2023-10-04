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
        Schema::table('waste_material_details', function (Blueprint $table) {
            $table->string('transaction_slug')->nullable();
            $table->string('stock_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waste_material_details', function (Blueprint $table) {
            $table->dropColumn('transaction_slug');
            $table->dropColumn('stock_no');
        });
    }
};
