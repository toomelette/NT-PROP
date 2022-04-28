<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePpuPrefix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ppu_moprocurement',function (Blueprint $table){
            $table->rename('moprocurement');
        });

        Schema::table('ppu_ppdo',function (Blueprint $table){
            $table->rename('ppdo');
        });

        Schema::table('ppu_ppmp',function (Blueprint $table){
            $table->rename('ppmp');
        });

        Schema::table('ppu_rc_description',function (Blueprint $table){
            $table->rename('rc_description');
        });

        Schema::table('ppu_rec_budget',function (Blueprint $table){
            $table->rename('pap');
        });

        Schema::table('ppu_resp_codes',function (Blueprint $table){
            $table->rename('resp_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moprocurement',function (Blueprint $table){
            $table->rename('ppu_moprocurement');
        });

        Schema::table('ppdo',function (Blueprint $table){
            $table->rename('ppu_ppdo');
        });

        Schema::table('ppmp',function (Blueprint $table){
            $table->rename('ppu_ppmp');
        });

        Schema::table('rc_description',function (Blueprint $table){
            $table->rename('ppu_rc_description');
        });

        Schema::table('pap',function (Blueprint $table){
            $table->rename('ppu_rec_budget');
        });

        Schema::table('resp_codes',function (Blueprint $table){
            $table->rename('ppu_resp_codes');
        });
    }
}
