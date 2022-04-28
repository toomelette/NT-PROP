<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReaarrangePap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `swep_ppu`.`pap` 
        ADD COLUMN `base_pap_code` INT NOT NULL AFTER `budget_type`,
        CHANGE COLUMN `resp_center` `resp_center` VARCHAR(15) NOT NULL AFTER `year`,
        CHANGE COLUMN `pap_code` `pap_code` VARCHAR(20) NOT NULL AFTER `base_pap_code`,
        CHANGE COLUMN `fiscal_year` `year` INT(4) NOT NULL ;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
