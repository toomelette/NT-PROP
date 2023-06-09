<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundCluster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_cluster', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('fund_cluster');
    }
}
