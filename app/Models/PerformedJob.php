<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformedJob extends Model
{
    protected $fillable = ['query_id', 'tempo_atualizacao', 'erro'];

    public function querys()
    {
        return $this->belongsTo(Query::class, 'query_id', 'id');
    }
}

