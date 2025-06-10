<?php

namespace App\Livewire\Consulta;

use App\Jobs\AtualizacaoJob;
use App\Models\Module;
use App\Models\Query;
use App\Models\RunningJob;
use App\Models\SubModulo;
use App\Models\Value;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Computed;

class MostrarConsulta extends Component
{

    public $modulo;
    public $idParaDeletar;
    public $idParaEditar;
    public $consultasAgrupadas; // Changed from $consultas to $consultasAgrupadas
    public $modulo_modal;
    public $titulo_modal;
    public $atualizacao_modal;
    public $consulta_modal;
    public $oldConsulta_modal;
    public ?int $qtde_critica;
    public $submodulo;
    public $modulo_id;
    public $horario_execucao;
    public $tituloIA;
    public $respostaIA;

    public function deletarConsulta(Query $id)
    {
        $this->idParaDeletar = $id;

        Flux::modal('deletar-consulta')->show();
    }

    public function deletar()
    {
        $this->idParaDeletar->delete();

        Flux::toast(
            heading: 'Sucesso',
            text: 'Consulta Deletada Com Sucesso.',
            variant: 'success',
        );

        $this->reset(['idParaDeletar']);

        Flux::modal('deletar-consulta')->close(); // Changed from show() to close()
    }

    public function editarConsulta(Query $id)
    {
        $this->idParaEditar = $id;
        $this->modulo_modal = $id->modulo;
        $this->titulo_modal = $id->titulo;
        $this->atualizacao_modal = $id->atualizacao;
        $this->consulta_modal = $id->consulta;
        $this->oldConsulta_modal = $id->consulta;
        $this->submodulo = $id->submodulo_id;
        $this->horario_execucao = json_decode($id->horarios_execucao);
        $this->qtde_critica = $id->qtde_critica;

        Flux::modal('editar-consulta')->show();
    }

    public function editar()
    {
        try {
            $this->idParaEditar->update([
                'modulo' => $this->modulo_modal,
                'titulo' => trim($this->titulo_modal),
                'atualizacao' => $this->atualizacao_modal,
                'consulta' => trim(str_ireplace(['@DBLSERVIDOR', ';', ' INSERT ', 'DATABASE', ' DELETE ', ' DROP ', ' UPDATE ', ' ALTER ', ' GRANT ', ' REVOKE ', ' COMMIT ', ' ROLLBACK ', ' SAVEPOINT ', ' TRUNCATE ', ' GRANT ROLE ', ' REVOKE ROLE ', ' MODIFY ', ' CHANGE '], '', $this->consulta_modal)),
                'submodulo_id' => $this->submodulo,
                'horarios_execucao' => $this->horario_execucao,
                'qtde_critica' => ($this->qtde_critica ?? null),
            ]);

            if ($this->consulta_modal !== $this->oldConsulta_modal) {
                $value = Value::where('query_id', $this->idParaEditar->id)->first();
                if ($value) {
                    $value->delete();
                }
            }

            //$this->dispatch('consulta-editada', ['modulo' => $this->query->module->modulo ?? 'Sem Modulo Cadastrado']);

            Flux::toast(
                heading: 'Sucesso',
                text: 'Consulta atualizada com sucesso.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Erro',
                text: $e->getMessage(),
                variant: 'danger',
            );
        }

        Flux::modal('editar-consulta')->close();
    }

    #[On('consulta-editada')]
    public function mount($modulo)
    {
        $this->modulo = Module::where('modulo', $modulo)->firstOrFail();
        $this->modulo_id = $this->modulo->id;
    }

    public function relatorio(Query $query)
    {
        if ($query->values) {
            $this->redirectRoute('relatorio', ['tabela' => $query->tabela]);
        } else {
            $this->atualizar($query);
        }
    }

    public function atualizar(Query $query)
    {
        /*if (Gate::denies('usuario-admin')) {
            abort(403, 'Você não tem permissão para esta ação');
        }*/

        if (RunningJob::where('query_id', $query->id)->exists()) {

            Flux::toast(
                heading: 'Atenção',
                text: 'Atualização ja esta sendo executada.',
                variant: 'warning',
            );

            return;
        }

        AtualizacaoJob::dispatch($query);

        RunningJob::create(['query_id' => $query->id]);

        Flux::toast(
            heading: 'Sucesso',
            text: 'Atualização da consulta foi enfileirada e em breve estara disponivel.',
            variant: 'success',
        );
    }

    #[On('submodulo-criado')]
    #[Computed]
    public function submodulos()
    {
        return SubModulo::where('modulo_id', $this->modulo_id)->get();
    }

    public function iaResposta($id)
    {
        // 1. Recupera os dados
        $dados = Query::where('queries.id', $id)
            ->join('values', 'queries.id', '=', 'values.query_id')
            ->select('queries.titulo', 'values.valor')
            ->first();

        if (!$dados) {
            Flux::toast(
                heading: 'Atenção',
                text: 'Dados da consulta não encontrados.',
                variant: 'warning',
            );

            return;
        }

        $titulo = $dados->titulo;
        $valores = json_decode($dados->valor, true);
        $valores = array_slice($valores, 0, 100); // pega só 100 linhas

        // 2. Monta o prompt para a IA
        $prompt = <<<PROMPT
Você é um analista de dados especializado no ERP WinThor. Receberá abaixo o título e os resultados de uma consulta SQL criada por um DBA com o objetivo de identificar erros ou anomalias no sistema.

Sua função é:
1. Analisar os dados retornados.
2. Identificar padrões, falhas ou valores fora do esperado.
3. Sugerir possíveis causas ou ações que o usuário deve tomar.

Responda no seguinte formato:
- Resumo geral dos dados
- Possíveis causas
- Sugestões de ação
- Grau de severidade (baixo/médio/alto)

Título da consulta: "{$titulo}"

Resultados:
PROMPT;

        $prompt .= "\n";
        foreach ($valores as $linha) {
            foreach ($linha as $chave => $valor) {
                $prompt .= "$chave: $valor | ";
            }
            $prompt .= "\n";
        }

        // 3. Configura a requisição cURL para a OpenAI
        $apiKey = config('app.openai_api_key'); // defina isso no seu .env

        $postData = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um analista de dados experiente.'],
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

        Flux::modal('ia-resposta')->show();

        // 4. Exibe no modal ou retorna para Livewire/Blade
        $this->tituloIA = $titulo;
        $this->respostaIA = $respostaIA;
    }

    public function render()
    {
        // Get all queries for this module
        $queries = Query::where('modulo', $this->modulo->id)
            ->with('values')
            ->orderBy('submodulo_id')
            ->orderBy('titulo')
            ->get();

        // Group queries by submodule_id manually to avoid collection issues
        $grouped = [];
        foreach ($queries as $query) {
            $key = $query->submodulo_id ?? 'null';
            if (!isset($grouped[$key])) {
                $grouped[$key] = collect();
            }
            $grouped[$key]->push($query);
        }

        $this->consultasAgrupadas = collect($grouped);

        // Get submodules for display names
        $submodulos = SubModulo::where('modulo_id', $this->modulo->id)->get()->keyBy('id');

        return view('livewire.consulta.mostrar-consulta', [
            'consultasAgrupadas' => $this->consultasAgrupadas,
            'submodulos' => $submodulos
        ]);
    }
}