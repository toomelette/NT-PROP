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
        Schema::create('notice_to_proceed', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('notice_number')->nullable();
            $table->string('document_no')->nullable();
            $table->date('date')->nullable();
            $table->string('supplier')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_representative')->nullable();
            $table->string('supplier_representative_position')->nullable();
            $table->string('content')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('approved_by_designation')->nullable();
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
        Schema::dropIfExists('notice_to_proceed');
    }
};
