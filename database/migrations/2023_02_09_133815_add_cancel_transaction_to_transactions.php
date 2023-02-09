<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelTransactionToTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dateTime('cancelled_at')->nullable();
            $table->string('user_cancelled')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->dropColumn('rfq_created_at');
            $table->dropColumn('rfq_user_created');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('cancelled_at','user_cancelled','cancellation_reason');
        });
    }
}
