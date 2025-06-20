<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $fillable = ['modulo', 'tabela', 'titulo', 'atualizacao', 'consulta', 'submodulo_id','horarios_execucao','qtde_critica','whatsapp','whatsapp_prompt','whatsapp_usuarios'];

    public function values()
    {
        return $this->hasOne(Value::class, 'tabela', 'tabela');
    }

    public function module()
    {
        return $this->hasOne(Module::class, 'id', 'modulo');
    }

    public function submodulo()
    {
        return $this->belongsTo(SubModulo::class);
    }

    public function runningJob()
    {
        return $this->hasOne(RunningJob::class);
    }

    public function value_totals()
    {
        return $this->hasMany(ValueTotal::class, 'query_id', 'id');
    }
}
