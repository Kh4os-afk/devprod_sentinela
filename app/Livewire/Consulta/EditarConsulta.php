<?php

namespace App\Livewire\Consulta;

use App\Models\Query;
use App\Models\Value;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;

class EditarConsulta extends ModalComponent
{
    use LivewireAlert;

    public $id;
    public $modulo;
    public $titulo;
    public $tabela = '';
    public $sugestoes = [];

    public $consulta;
    public $atualizacao;
    public $oldConsulta;
    public $query;

    public function rules()
    {
        return [
            'modulo' => 'required',
            'titulo' => 'required|min:5',
            'tabela' => [
                'required',
                'min:5',
                Rule::unique('queries', 'tabela')->ignore($this->id)
            ],
            'consulta' => 'required|min:5',
            'atualizacao' => 'required|min:1'
        ];
    }

    public function mount(Query $id)
    {
        $this->id = $id->id;
        $this->modulo = $id->modulo;
        $this->titulo = $id->titulo;
        $this->tabela = $id->tabela;
        $this->consulta = $id->consulta;
        $this->atualizacao = $id->atualizacao;
        $this->oldConsulta = $id->consulta;
        $this->query = $id;

    }

    public function submit()
    {
        $this->validate();

        $query = Query::findOrFail($this->id);
        if ($query) {
            $query->update([
                'modulo' => $this->modulo,
                'titulo' => trim($this->titulo),
                'tabela' => trim($this->tabela),
                'consulta' => trim(str_ireplace(['@DBLSERVIDOR', ';', ' INSERT ', 'DATABASE', ' DELETE ', ' DROP ', ' UPDATE ', ' ALTER ', ' GRANT ', ' REVOKE ', ' COMMIT ', ' ROLLBACK ', ' SAVEPOINT ', ' TRUNCATE ', ' GRANT ROLE ', ' REVOKE ROLE ', ' MODIFY ', ' CHANGE '], '', $this->consulta)),
                'atualizacao' => $this->atualizacao,
            ]);

            if ($this->consulta !== $this->oldConsulta) {
                $value = Value::where('tabela', $this->tabela)->first();
                if ($value) {
                    $value->delete();
                }
            }

            $this->alert('success', 'Consulta Editada Com Sucesso!', [
                'timerProgressBar' => true,
            ]);

            $this->dispatch('consulta-editada', ['modulo' => $this->query->module->modulo ?? 'Sem Modulo Cadastrado']);

        } else {
            $this->alert('error', 'Consulta não encontrada', [
                'timerProgressBar' => true,
            ]);
        }
    }

    public function updatedTabela()
    {
        $this->sugestoes = Query::where('tabela', 'LIKE', "%{$this->tabela}%")->pluck('tabela');
        if ($this->sugestoes->isEmpty()) {
            $this->sugestoes = ['Nome de Tabela Correto'];
        }
    }

    public function render()
    {
        return view('livewire.consulta.editar-consulta');
    }
}
