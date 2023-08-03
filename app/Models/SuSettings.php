<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SuSettings extends Model
{
    protected $connection = 'mysql_ppu';
    protected $table = 'su_settings';

    public $timestamps = false;
}