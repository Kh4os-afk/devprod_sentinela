<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModulo extends Model
{
    protected $fillable = ['modulo_id', 'submodulo'];

    public function consultas()
    {
        return $this->hasMany(Query::class, 'submodulo_id');
    }

    public function modulo()
    {
        return $this->belongsTo(Module::class);
    }

    public function queries()
    {
        return $this->hasMany(Query::class);
    }
}