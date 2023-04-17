<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCRTable extends Migration
{
    public function up()
    {
        Schema::table('cancellation_request', function (Blueprint $table) {
            $table->boolean('is_cancelled')->default(false);
        });
    }

    public function down()
    {
        Schema::table('cancellation_request', function (Blueprint $table) {
            $table->dropColumn('is_cancelled');
        });
    }
}
