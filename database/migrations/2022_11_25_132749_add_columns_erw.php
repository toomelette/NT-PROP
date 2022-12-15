<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsErw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pap', function (Blueprint $table) {
            $table->string('status')->after('pcent_share')->nullable();
            $table->string('type')->after('pcent_share')->nullable();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pap', function (Blueprint $table) {
            $table->dropColumn('type','status');
        });
    }
}
