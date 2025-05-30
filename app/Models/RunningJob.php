<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunningJob extends Model
{
    protected $fillable = ['query_id'];

    public function querys()
    {
        return $this->belongsTo(Query::class, 'query_id', 'id');
    }
}
