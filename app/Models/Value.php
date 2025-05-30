<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $fillable = ['query_id', 'tabela', 'valor'];

    public function querys()
    {
        return $this->hasOne(Query::class, 'tabela', 'tabela');
    }
}
