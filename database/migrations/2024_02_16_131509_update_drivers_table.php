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
        Schema::table('drivers', function (Blueprint $table) {
           $table->renameColumn('slug', 'employee_slug');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->renameColumn('employee_slug', 'slug');
            $table->dropColumn('slug');
        });
    }
};