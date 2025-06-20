<?php

namespace App\Jobs;

use App\Models\Query;
use Flux\Flux;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappNotificacao implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Query $query)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->query->values?->valor) {
            Log::error('Tentativa de enviar notificação via WhatsApp sem dados disponíveis', [
                'query_id' => $this->query->id,
                'titulo' => $this->query->titulo,
            ]);

            return;
        }

        $usuarios = json_decode($this->query->whatsapp_usuarios);

        if (empty($usuarios)) {
            Log::warning('Consulta sem usuários de WhatsApp configurados', [
                'query_id' => $this->query->id,
                'titulo' => $this->query->titulo,
            ]);
            return;
        }

        // 1. Extrai os dados da consulta
        $valores = json_decode($this->query->values->valor, true);
        // 2. Pega as primeiras 100 linhas.
        $valores = array_slice($valores, 0, 100); // limita a 100 linhas
        // 3. Verifica o Prompt
        $prompt = $this->query->whatsapp_prompt;

        // 3. Monta o prompt para a IA
        $prompt .= "\n";
        $prompt .= <<<PROMPT
Título da consulta: "{$this->query->titulo}"

Resultados:
PROMPT;

        foreach ($valores as $linha) {
            foreach ($linha as $chave => $valor) {
                $prompt .= "$chave: $valor | ";
            }
            $prompt .= "\n";
        }

        // 5. Envia para OpenAI
        $apiKey = config('app.openai_api_key');

        $postData = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($postData),
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $decoded = json_decode($response, true);
        $respostaIA = $decoded['choices'][0]['message']['content'] ?? 'Não foi possível gerar uma resposta.';

        // 6. Envia via WhatsApp (WAHA)
        foreach ($usuarios as $usuarioId) {
            $user = \App\Models\User::find($usuarioId);

            if (!$user || !$user->fone) {
                Log::warning('Usuário inválido ou sem telefone', [
                    'user_id' => $usuarioId,
                ]);
                continue;
            }

            // Limpa e formata o telefone com DDI (remove 9 extra após o DDD)
            $numero = '55' . substr_replace(
                    str_ireplace(['(', ')', ' ', '-'], '', $user->fone),
                    '', 2, 1 // remove o 9 extra do número brasileiro
                );

            // Envia via WAHA
            $wahaResponse = Http::post('http://172.22.22.174:5600/api/sendText', [
                'chatId' => $numero . '@c.us',
                'reply_to' => null,
                'text' => $respostaIA,
                'linkPreview' => false,
                'linkPreviewHighQuality' => false,
                'session' => 'default',
            ]);

            if ($wahaResponse->successful()) {
                Log::info('Mensagem enviada via WAHA', [
                    'to' => $numero,
                    'query_id' => $this->query->id,
                ]);
            } else {
                Log::error('Erro ao enviar via WAHA', [
                    'to' => $numero,
                    'error' => $wahaResponse->body(),
                ]);
            }
        }
    }
}
