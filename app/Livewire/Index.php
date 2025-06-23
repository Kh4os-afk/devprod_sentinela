<?php

namespace App\Livewire;

use App\Models\Query;
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
        return view('livewire.index');
    }
}
