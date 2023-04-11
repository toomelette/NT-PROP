<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardNoticeAbstract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award_notice_abstract', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('award_notice_number')->nullable();
            $table->string('title_of_notice')->nullable();
            $table->date('award_date')->nullable();
            $table->string('registry_number')->nullable();
            $table->string('ref_book')->nullable();
            $table->string('ref_number')->nullable();
            $table->string('title')->nullable();
            $table->string('category')->nullable();
            $table->decimal('approved_budget',20,2)->nullable();
            $table->decimal('contract_amount',20,2)->nullable();
            $table->string('remarks')->nullable();
            $table->string('reason_for_award')->nullable();
            $table->string('awardee')->nullable();
            $table->string('awardee_address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_address')->nullable();
            $table->string('phone_number_1')->nullable();
            $table->string('phone_number_2')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('corporate_title')->nullable();
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
        Schema::dropIfExists('award_notice_abstract');
    }
}
