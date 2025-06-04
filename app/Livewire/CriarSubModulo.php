<?php

namespace App\Livewire;

use App\Models\Module;
use App\Models\SubModulo;
use Flux\Flux;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Attributes\Reactive;

class CriarSubModulo extends Component
{
    #[Rule('required|numeric')]
    #[Reactive]
    public $modulo;
    #[Rule('required|min:3|string', as: 'submodulo')]
    public $submodulo;

    public function submit()
    {
        $this->validate();

        SubModulo::create([
            'modulo_id' => $this->modulo,
            'submodulo' => $this->submodulo,
        ]);

        $this->reset(['submodulo']);

        $this->dispatch('submodulo-criado');

        Flux::toast(
            heading: 'Sucesso',
            text: 'Sub Modulo Criado com Sucesso.',
            variant: 'success',
        );
    }

    public function delete(SubModulo $id)
    {
        try {
            $id->delete();

            Flux::toast(
                heading: 'Sucesso',
                text: 'Sub Modulo deletado com Sucesso.',
                variant: 'success',
            );

            $this->dispatch('submodulo-criado');
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Error',
                text: $e->getMessage(),
                variant: 'danger',
            );
        }
    }

    public function render()
    {
        return view('livewire.criar-sub-modulo');
    }
}
