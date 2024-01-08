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
        Schema::table('award_notice_abstract', function (Blueprint $table) {
            $table->integer('project_id')->nullable();
        });

        Schema::table('notice_of_award', function (Blueprint $table) {
            $table->integer('project_id')->nullable();
        });

        Schema::table('notice_to_proceed', function (Blueprint $table) {
            $table->integer('project_id')->nullable();
        });

        Schema::table('order', function (Blueprint $table) {
            $table->integer('project_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('award_notice_abstract', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });

        Schema::table('notice_of_award', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });

        Schema::table('notice_to_proceed', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });

        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
};
