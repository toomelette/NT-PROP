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
        Schema::table('email_recipients', function (Blueprint $table) {
            $table->integer('receive_procurement_updates')->nullable();
            $table->integer('receive_transportation_updates')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_recipients', function (Blueprint $table) {
            $table->dropColumn(['receive_procurement_updates','receive_transportation_updates']);

        });
    }
};
