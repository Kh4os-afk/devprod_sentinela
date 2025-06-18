<?php

namespace App\Livewire;

use App\Models\Query;
use App\Models\Value;
use Flux\Flux;
use Livewire\Component;

class EditarConsulta extends Component
{
    public $query;
    public $modulo, $titulo, $horario_execucao, $consulta, $whatsapp_prompt;
    public $oldConsulta_modal;

    public function mount(Query $query)
    {
        $this->query = $query;
        $this->modulo = $query->modulo;
        $this->titulo = $query->titulo;
        $this->horario_execucao = json_decode($query->horarios_execucao);
        $this->whatsapp_prompt = $query->whatsapp_prompt;
        $this->consulta = $query->consulta;
        $this->oldConsulta_modal = $query->consulta;
    }

    public function editar()
    {
        try {
            $this->query->update([
                'modulo' => $this->modulo,
                'titulo' => $this->titulo,
                'consulta' => trim(str_ireplace(['@DBLSERVIDOR', ';', ' INSERT ', 'DATABASE', ' DELETE ', ' DROP ', ' UPDATE ', ' ALTER ', ' GRANT ', ' REVOKE ', ' COMMIT ', ' ROLLBACK ', ' SAVEPOINT ', ' TRUNCATE ', ' GRANT ROLE ', ' REVOKE ROLE ', ' MODIFY ', ' CHANGE '], '', $this->consulta)),
                'horarios_execucao' => $this->horario_execucao,
                'whatsapp_prompt' => $this->whatsapp_prompt,
            ]);

            if ($this->consulta !== $this->oldConsulta_modal) {
                $value = Value::where('query_id', $this->query->id)->first();
                if ($value) {
                    $value->delete();
                }
            }

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

    public function render()
    {
        return view('livewire.editar-consulta');
    }
}
