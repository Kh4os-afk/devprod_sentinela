<?php

use App\Jobs\AtualizacaoJob;
use App\Models\Query;
use App\Models\Value;
use App\Models\ValueTotal;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    try {
        $querys = Query::all();
        foreach ($querys as $query) {
            if (now()->diffInHours($query->values ? $query->values->updated_at : $query->updated_at) >= $query->atualizacao) {
                AtualizacaoJob::dispatch($query);
                $query->values ? $query->values->touch() : $query->touch();
                Log::info('Atualização Despachada na fila as ' . now()->format('d/m/Y H:i:s') . ' do Job ' . $query->titulo);
            }
        }
    } catch (\Exception $e) {
        Log::error('Erro durante a execução do atualizador: ' . $e->getMessage());
    }
})->everyMinute();

Schedule::call(function () {
    foreach (Value::all() as $value) {
        ValueTotal::create([
            'query_id' => $value->query_id,
            'total' => count(json_decode($value->valor)),
        ]);
    }
})->dailyAt('22:00');