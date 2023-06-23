<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_code', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('code')->nullable();
            $table->string('class')->nullable();
            $table->string('description')->nullable();
            $table->string('account_group')->nullable();
            $table->string('major_account_group')->nullable();
            $table->string('sub_major_account_group')->nullable();
            $table->string('general_ledger_account')->nullable();
            $table->string('general_ledger_contract')->nullable();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('ip_created')->nullable();
            $table->string('ip_updated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_code');
    }
}
