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
        Schema::dropIfExists('notice_of_award');

        Schema::create('notice_of_award', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('notice_number')->nullable();
            $table->string('document_no')->nullable();
            $table->date('date')->nullable();
            $table->string('supplier')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_representative')->nullable();
            $table->string('supplier_representative_position')->nullable();
            $table->string('project_name')->nullable();
            $table->string('content')->nullable();
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
        Schema::dropIfExists('notice_of_award');
    }
};
