<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('resp_center')->nullable();
            $table->string('pap_code')->nullable();
            $table->string('ref_book')->nullable();
            $table->string('ref_no')->nullable();
            $table->date('date')->nullable();
            $table->decimal('abc',20,2)->nullable();
            $table->string('sai')->nullable();
            $table->date('sai_date')->nullable();
            $table->string('purpose')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('requested_by_designation')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('approved_by_designation')->nullable();
            $table->string('certified_by')->nullable();
            $table->string('certified_by_designation')->nullable();
            $table->timestamps();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
