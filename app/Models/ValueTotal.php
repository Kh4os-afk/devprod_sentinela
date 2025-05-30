<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValueTotal extends Model
{
    protected $fillable = ['query_id', 'total'];

    public function querys()
    {
        return $this->belongsTo(Query::class, 'query_id', 'id');
    }
}
