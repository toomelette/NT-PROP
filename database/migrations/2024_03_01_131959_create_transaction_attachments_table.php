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
        Schema::create('transaction_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('path_market_survey')->nullable();
            $table->string('path_specs')->nullable();
            $table->string('path_ppmp')->nullable();
            $table->string('path_app')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_attachments');
    }
};
