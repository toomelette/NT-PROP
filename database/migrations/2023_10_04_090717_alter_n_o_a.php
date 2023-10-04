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
        Schema::table('notice_of_award', function (Blueprint $table) {
            $table->string('ref_no')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('approved_by_designation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notice_of_award', function (Blueprint $table) {
            $table->dropColumn(['ref_no','approved_by','approved_by_designation']);
        });
    }
};
