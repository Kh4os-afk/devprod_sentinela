<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['id', 'modulo', 'icon', 'extra'];

    public function querys()
    {
        return $this->hasMany(Query::class, 'modulo', 'id');
    }
}
