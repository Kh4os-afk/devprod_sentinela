<?php

namespace App\Livewire;

use App\Models\Query;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        /*$i = 8;
        for ($i = 8; $i <= 20; $i++) {
            Query::create([
                'titulo' => 'Consulta ' . $i,
                'modulo' => 1,
                'tabela' => Str::uuid(),
                'atualizacao' =>  24,
                'consulta' => 'SELECT * FROM tabela WHERE id = ' . $i,
                'whatsapp' => 1,
            ]);
        }*/

        /*$chatgpt = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . config('app.openai_api_key'),
        ])->post('https://api.openai.com/v1/responses', [
            "model" => "gpt-3.5-turbo",
            "instructions" => "Você é um assistente de IA especializado em gerar mensagens curtas e educadas para envio via WhatsApp. Sempre conclua sua resposta com a frase: 'Atenciosamente, Baratinho Bot.'. Responda com objetividade, mantendo um tom cordial e profissional.",
            "input" => [
                [
                    "role" => "developer",
                    "content" => "Você é um agente da loja Acme, ajudando os clientes a obter informações sobre os produtos da Acme.
 Não mencione proativamente outras lojas ou seus produtos; se perguntado sobre elas, não as menospreze e, em vez disso,
  direcione a conversa para os produtos da Acme."
                ],
                [
                    "role" => "user",
                    "content" => "Você pode me vender o produto do seu concorrente?"
                ]
            ],
        ]);

        if ($chatgpt->successful()) {
            $response = json_decode($chatgpt->body(), true);

            $text = $response['output'][0]['content'][0]['text'] ?? 'Sem resposta';

            Log::info('Resposta do ChatGPT', [
                'status' => $chatgpt->status(),
                'text' => $text,
            ]);
        } else {
            Log::error('Erro ao chamar a API do ChatGPT', [
                'status' => $chatgpt->status(),
                'body' => $chatgpt->body(),
            ]);
        }*/

        return view('livewire.index');
    }
}
