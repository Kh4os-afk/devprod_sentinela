<?php

namespace App\Livewire;

use App\Models\Query;
use Livewire\Component;
use Livewire\Attributes\Computed;

class CommandSearch extends Component
{
    public function relatorio(Query $query)
    {
        if ($query->values) {
            $this->redirectRoute('relatorio', ['tabela' => $query->tabela]);
        } else {
            $this->atualizar($query);
        }
    }

    #[Computed]
    public function queries()
    {
        return Query::join('values', 'values.query_id', '=', 'queries.id')
            ->select('queries.*')
            ->orderBy('queries.titulo','asc')
            ->get();
    }
    public function render()
    {
        return view('livewire.command-search');
    }
}
