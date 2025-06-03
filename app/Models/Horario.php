<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }
}
