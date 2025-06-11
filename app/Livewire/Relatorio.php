<?php

namespace App\Livewire;

use App\Models\UserAccess;
use App\Models\Value;
use App\Models\ValueTotal;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Relatorio extends Component
{
    public $relatorios;
    public $titulo = '';
    public $data = '';
    public $query_id;

    #[Computed]
    public function grafico1()
    {
        return ValueTotal::where('query_id', $this->query_id)
            ->whereDate('created_at', '>=', today()->subDays(30))
            ->select('created_at', 'total')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'created_at' => $item->created_at->format('Y-m-d'),
                    'total' => $item->total,
                ];
            })
            ->toArray();
    }

    public function mount($tabela)
    {
        $relatorios = Value::where('tabela', $tabela)->with('querys')->first();
        if ($relatorios) {
            $this->titulo = $relatorios->querys->titulo;
            $this->data = $relatorios->updated_at;
            $this->query_id = $relatorios->query_id;

            /*Registra o acesso do usuario na consulta*/
            UserAccess::registerAccess(auth()->user()->id, $relatorios->querys?->id);
        }

        try {
            $this->relatorios = json_decode($relatorios->valor);
        } catch (\Exception $e) {
            abort(501, 'Consulta ou Pagina Inexistente ' . $e->getMessage());
        }
    }

    public function render()
    {
        /*dd(ValueTotal::where('query_id', $this->query_id)
            ->whereDate('created_at', '>=', today()->subDay(10))
            ->select('created_at', 'total')
            ->get()
            ->toArray());*/

        return view('livewire.relatorio')
            ->title($this->titulo);
    }
}
