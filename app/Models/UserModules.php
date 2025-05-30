<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModules extends Model
{
    protected $fillable = ['user_id', 'module_id', 'add_by'];
}
