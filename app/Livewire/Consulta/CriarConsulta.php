<?php

namespace App\Livewire\Consulta;

use App\Models\Query;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class CriarConsulta extends Component
{
    public $modulo = 1;
    public $titulo;
    public $consulta;
    public $tabela = '';
    public $sugestoes = [];
    public $atualizacao = 24;

    public function rules()
    {
        return [
            'modulo' => 'required',
            'titulo' => 'required|min:5',
            'tabela' => 'required|min:5|unique:queries,tabela',
            'consulta' => 'required|min:5',
            'atualizacao' => 'required|min:1'
        ];
    }

    public function submit()
    {
        $validate = $this->validate();

        /*Percorre cada valor do Array passando o mesmo para Maisculo*/
        $validate = array_map('trim', $validate);

        /*Remove conjunto de instruções perigosas*/
        $validate = str_ireplace(['@DBLSERVIDOR', ';', ' INSERT ', 'DATABASE', ' DELETE ', ' DROP ', ' UPDATE ', ' ALTER ', ' GRANT ', ' REVOKE ', ' COMMIT ', ' ROLLBACK ', ' SAVEPOINT ', ' TRUNCATE ', ' GRANT ROLE ', ' REVOKE ROLE ', ' MODIFY ', ' CHANGE '], '', $validate);

        try {
            Query::create($validate);
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Error',
                text: $e->getMessage(),
                variant: 'danger',
            );
        }

        $this->reset();

        Flux::toast(
            heading: 'Sucesso',
            text: 'Consulta criada com sucesso.',
            variant: 'success',
        );
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
        return view('livewire.consulta.criar-consulta');
    }
}
