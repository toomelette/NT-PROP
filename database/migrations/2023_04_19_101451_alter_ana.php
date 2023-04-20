<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAna extends Migration
{
    public function up()
    {
        Schema::table('award_notice_abstract', function (Blueprint $table) {
            $table->string('organization_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('signatory')->nullable();
            $table->string('designation')->nullable();
        });
    }

    public function down()
    {
        Schema::table('award_notice_abstract', function (Blueprint $table) {
            $table->dropColumn('organization_name');
            $table->dropColumn('contact_name');
            $table->dropColumn('signatory');
            $table->dropColumn('designation');
        });
    }
}
