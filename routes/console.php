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
        $now = now()->format('H:i');
        $querys = Query::all();

        foreach ($querys as $query) {
            $horarios = is_array($query->horarios_execucao)
                ? $query->horarios_execucao
                : json_decode($query->horarios_execucao, true);

            if (in_array($now, $horarios ?? [])) {
                AtualizacaoJob::dispatch($query);
                Log::info("Consulta ID {$query->id} atualizada no horário {$now}");
            }
        }

        Log::info('Verificação de horários executada com sucesso às ' . now()->format('d/m/Y H:i:s'));
    } catch (\Exception $e) {
        Log::error('Erro durante a execução da verificação de horários: ' . $e->getMessage());
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

// Disparar email critico para os usuarios por modulo
Schedule::call(function () {
    foreach (\App\Models\Module::get() as $module) {
        (new \App\Services\DispararEmail())->emailCritico($module);
    }
})->dailyAt('12:00');
