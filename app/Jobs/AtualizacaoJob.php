<?php

namespace App\Jobs;

use App\Models\PerformedJob;
use App\Models\Query;
use App\Models\RunningJob;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;

class AtualizacaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2; // Número de tentativas
    public $timeout = 1200; // Timeout de 1200 segundos (20 minutos)

    protected $query;
    protected $startTime;


    /**
     * Create a new job instance.
     */
    public function __construct(Query $query)
    {
        $this->query = $query->withoutRelations();
        $this->startTime = Carbon::now();
    }

    /**
     * Middleware for the job.
     */
    public function middleware()
    {
        return [new WithoutOverlapping($this->query->id)];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $consulta = DB::connection('oracle')->select($this->query->consulta);
            if (!$consulta) {
                $consulta = [];
            }
            $resultado = json_encode($consulta);

            if (!$this->query->values) {
                $this->query->values()->create([
                    'query_id' => $this->query->id,
                    'tabela' => $this->query->tabela,
                    'valor' => $resultado,
                ]);
            } else {
                $this->query->values()->update([
                    'valor' => $resultado,
                ]);
            }

            /*Calcula o tempo de execução da query */
            $endTime = Carbon::now();
            $duration = $this->startTime->diffInMinutes($endTime);

            PerformedJob::create([
                'query_id' => $this->query->id,
                'tempo_atualizacao' => $duration, // Grava a duração em minutos
            ]);

            $this->query->runningJob ? $this->query->runningJob->delete() : null;
        } catch (\Exception $e) {
            PerformedJob::create([
                'query_id' => $this->query->id,
                'erro' => $e->getMessage(), // Grava a mensagem de erro
            ]);
            $this->query->runningJob ? $this->query->runningJob->delete() : null;
        }
    }

    public function failed(\Exception $exception)
    {
        PerformedJob::create([
            'query_id' => $this->query->id,
            'erro' => $exception->getMessage(), // Grava a mensagem de erro
        ]);

        $this->query->runningJob ? $this->query->runningJob->delete() : null;

        Log::error('AtualizacaoJob falhou permanentemente: ' . $exception->getMessage());
    }
}
