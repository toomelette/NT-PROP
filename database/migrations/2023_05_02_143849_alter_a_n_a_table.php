<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterANATable extends Migration
{
    public function up()
    {
        Schema::table('award_notice_abstract', function (Blueprint $table) {
            $table->string('awardee_tin')->nullable();
        });
    }

    public function down()
    {
        Schema::table('award_notice_abstract', function (Blueprint $table) {
            $table->dropColumn('awardee_tin');
        });
    }
}
