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
        Schema::table('property_card', function (Blueprint $table) {
            $table->string('prepared_by')->nullable();
            $table->string('prepared_by_designation')->nullable();
            $table->string('noted_by')->nullable();
            $table->string('noted_by_designation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_card', function (Blueprint $table) {
            $table->dropColumn('prepared_by');
            $table->dropColumn('prepared_by_designation');
            $table->dropColumn('noted_by');
            $table->dropColumn('noted_by_designation');
        });
    }
};
