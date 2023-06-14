<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterArticle1 extends Migration
{
    public function up()
    {
        Schema::table('inv_master', function (Blueprint $table) {
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
        });
    }

    public function down()
    {
        Schema::table('inv_master', function (Blueprint $table) {
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
        });
    }
}
