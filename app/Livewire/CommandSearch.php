<?php

namespace App\Livewire;

use App\Models\Query;
use Livewire\Component;

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
    public function render()
    {
        return view('livewire.command-search');
    }
}
