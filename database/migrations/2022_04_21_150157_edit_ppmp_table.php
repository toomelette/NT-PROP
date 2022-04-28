<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditPpmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `swep_ppu`.`ppmp` 
            ADD COLUMN `ps` DECIMAL(20,2) NULL AFTER `mode_of_proc`,
            ADD COLUMN `co` DECIMAL(20,2) NULL AFTER `ps`,
            ADD COLUMN `mooe` DECIMAL(20,2) NULL AFTER `co`,
            CHANGE COLUMN `budget_type` `source_of_fund` VARCHAR(10) NOT NULL ;
        ');
        DB::statement('ALTER TABLE `swep_ppu`.`ppmp` 
            CHANGE COLUMN `total_budget` `budget_type` VARCHAR(20) NOT NULL ;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ppmp',function (Blueprint $table){
            $table->dropColumn(['ps','co','mooe']);
            $table->renameColumn('source_of_fund','budget_type');
        });

        DB::statement('ALTER TABLE `swep_ppu`.`ppmp` 
            CHANGE COLUMN `budget_type` `total_budget`  DOUBLE NOT NULL ;
        ');
    }
}
