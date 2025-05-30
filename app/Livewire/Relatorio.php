<?php

namespace App\Livewire;

use App\Models\UserAccess;
use App\Models\Value;
use Livewire\Component;

class Relatorio extends Component
{
    public $relatorios;
    public $titulo = '';
    public $data = '';

    public function mount($tabela)
    {
        $relatorios = Value::where('tabela', $tabela)->with('querys')->first();
        if ($relatorios) {
            $this->titulo = $relatorios->querys->titulo;
            $this->data = $relatorios->updated_at;

            /*Registra o acesso do usuario na consulta*/
            UserAccess::registerAccess(auth()->user()->id, $relatorios->querys?->id);
        }

        try {
            $this->relatorios = json_decode($relatorios->valor);
        } catch (\Exception $e) {
            abort(501, 'Consulta ou Pagina Inexistente ' . $e->getMessage());
        }
    }

    public function placeholder()
    {
        return view('placeholder');
    }

    public function render()
    {
        return view('livewire.relatorio')
            ->title($this->titulo);
    }
}
