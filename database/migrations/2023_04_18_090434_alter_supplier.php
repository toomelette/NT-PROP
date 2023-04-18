<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSupplier extends Migration
{
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('office_contact_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_address')->nullable();
            $table->string('phone_number_1')->nullable();
            $table->string('phone_number_2')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('designation')->nullable();
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('office_contact_number');
            $table->dropColumn('contact_person');
            $table->dropColumn('contact_person_address');
            $table->dropColumn('phone_number_1');
            $table->dropColumn('phone_number_2');
            $table->dropColumn('fax_number');
            $table->dropColumn('designation');
        });
    }
}
