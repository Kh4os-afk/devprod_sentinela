<?php

namespace App\Livewire\Consulta;

use App\Models\Query;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Component;

class CriarConsulta extends Component
{
    public $modulo;
    public $titulo;
    public $consulta;

    public function rules()
    {
        return [
            'modulo' => 'required|numeric',
            'titulo' => 'required|min:5',
            'consulta' => 'required|min:5',
        ];
    }

    public function submit()
    {
        $validate = $this->validate();

        /*Percorre cada valor do Array tirando espaços vazios*/
        $validate = array_map('trim', $validate);

        /*Remove conjunto de instruções perigosas*/
        $validate = str_ireplace(['@DBLSERVIDOR', ';', ' INSERT ', 'DATABASE', ' DELETE ', ' DROP ', ' UPDATE ', ' ALTER ', ' GRANT ', ' REVOKE ', ' COMMIT ', ' ROLLBACK ', ' SAVEPOINT ', ' TRUNCATE ', ' GRANT ROLE ', ' REVOKE ROLE ', ' MODIFY ', ' CHANGE '], '', $validate);

        try {
            Query::create([
                'modulo' => $validate['modulo'],
                'titulo' => $validate['titulo'],
                'tabela' => (string)Str::uuid(),
                'externo' => false,
                'atualizacao' => 0,
                'horarios_execucao' => json_encode(["00:00"]),
                'consulta' => $validate['consulta'],
            ]);
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Error',
                text: $e->getMessage(),
                variant: 'danger',
            );
            return;
        }

        $this->reset();

        Flux::toast(
            heading: 'Sucesso',
            text: 'Consulta criada com sucesso.',
            variant: 'success',
        );
    }

    public function render()
    {
        return view('livewire.consulta.criar-consulta');
    }
}
