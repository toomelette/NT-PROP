<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransactionTable extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('remarks')->nullable();
        });
    }

    public function down()
    {
        Schema::table('award_notice_abstract', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
}
