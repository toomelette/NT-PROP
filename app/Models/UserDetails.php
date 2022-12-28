<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $connection = 'mysql_ppu';
    protected $table = 'user_details';
}