<?php

namespace App\Jobs;

use App\Models\Query;
use Flux\Flux;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappNotificacaoJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Query $query)
    {
        //
    }

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

        // Extrai e limita os dados
        $valores = json_decode($this->query->values->valor, true);
        $valores = array_slice($valores, 0, 100);

        // Prompt do developer
        $developerPrompt = $this->query->whatsapp_prompt ?? 'Gere um resumo breve e direto com base nos dados da consulta abaixo.';

        // Prompt do usuário
        $userPrompt = "Título da consulta: \"{$this->query->titulo}\"\n\nResultados:\n";

        foreach ($valores as $linha) {
            foreach ($linha as $chave => $valor) {
                $userPrompt .= "$chave: $valor | ";
            }
            $userPrompt .= "\n";
        }

        $chatgpt = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . config('app.openai_api_key'),
        ])->post('https://api.openai.com/v1/responses', [
            "model" => "gpt-3.5-turbo",
            "instructions" => "Você é um assistente de IA especializado em gerar mensagens curtas e educadas para envio via WhatsApp. Sempre conclua sua resposta com a frase: 'Atenciosamente, Baratinho Bot.'. Responda com objetividade, mantendo um tom cordial e profissional.",
            "input" => [
                [
                    "role" => "developer",
                    "content" => $developerPrompt
                ],
                [
                    "role" => "user",
                    "content" => $userPrompt
                ]
            ],
        ]);

        if ($chatgpt->successful()) {
            $response = json_decode($chatgpt->body(), true);

            $text = $response['output'][0]['content'][0]['text'] ?? 'Não foi possível gerar uma resposta.';

            Log::info('Resposta do ChatGPT', [
                'status' => $chatgpt->status(),
                'text' => $text,
            ]);
        } else {
            Log::error('Erro ao chamar a API do ChatGPT', [
                'status' => $chatgpt->status(),
                'body' => $chatgpt->body(),
            ]);

            return;
        }

        // Envia via WhatsApp
        foreach ($usuarios as $usuarioId) {
            $user = \App\Models\User::find($usuarioId);

            if (!$user || !$user->fone) {
                Log::warning('Usuário inválido ou sem telefone', ['user_id' => $usuarioId]);
                continue;
            }

            $numero = '55' . substr_replace(
                    str_ireplace(['(', ')', ' ', '-'], '', $user->fone),
                    '', 2, 1
                );

            $wahaResponse = Http::post('http://172.22.22.174:5600/api/sendText', [
                'chatId' => $numero . '@c.us',
                'reply_to' => null,
                'text' => $text,
                'linkPreview' => false,
                'linkPreviewHighQuality' => false,
                'session' => 'default',
            ]);

            if ($wahaResponse->successful()) {
                Log::info('Mensagem enviada via WAHA', [
                    'numero' => $numero,
                    'query_id' => $this->query->id,
                ]);
            } else {
                Log::error('Erro ao enviar via WAHA', [
                    'numero' => $numero,
                    'error' => $wahaResponse->body(),
                ]);
            }
        }
    }
}
