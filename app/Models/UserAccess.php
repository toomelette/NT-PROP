<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    protected $connection = 'mysql_ppu';
    protected $table = 'users_access';
}