<?php

namespace App\Livewire;

use App\Models\Module;
use App\Models\SubModulo;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CriarSubModulo extends Component
{

    #[Rule('required')]
    public $modulo = 1;
    #[Rule('required|min:3|string', as: 'submodulo')]
    public $submodulo;

    public function submit()
    {
        $this->validate();

        SubModulo::create([
            'modulo_id' => $this->modulo,
            'submodulo' => $this->submodulo,
        ]);

        /*$this->alert('success', 'Sub Modulo Criado com Sucesso!', [
            'timerProgressBar' => true,
        ]);*/

        $this->reset();
    }

    public function render()
    {
        return view('livewire.criar-sub-modulo');
    }
}
