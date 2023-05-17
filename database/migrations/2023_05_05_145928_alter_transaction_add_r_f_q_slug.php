<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransactionAddRFQSlug extends Migration
{
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->string('rfq_slug')->nullable();
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('rfq_slug');
        });
    }
}
