<?php

namespace App\Livewire;

use App\Models\Module;
use App\Models\User;
use App\Models\UserModules;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;

class EditarUsuario extends Component
{
    use WithFileUploads;

    public $nome, $email, $fone;
    public $usuarioModulos;
    public $foto;

    public function salvar()
    {
        try {
            $foto = $this->foto->store('fotos','public');

            auth()->user()->update([
                'name' => $this->nome,
                'email' => $this->email,
                'fone' => $this->fone,
                'foto' => $foto,
            ]);

            Flux::toast(
                heading: 'Sucesso',
                text: 'Dados atualizados com sucesso.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Error',
                text: $e->getMessage(),
                variant: 'danger',
            );
        }
    }
    public function salvarPreferencias()
    {

        try {
            UserModules::where('user_id', auth()->user()->id)
                ->whereIn('id', $this->usuarioModulos)
                ->update(['responsavel' => true]);
            UserModules::where('user_id', auth()->user()->id)
                ->whereNotIn('id', $this->usuarioModulos)
                ->update(['responsavel' => false]);

            Flux::toast(
                heading: 'Sucesso',
                text: 'Preferencias atualizadas com sucesso.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Error',
                text: $e->getMessage(),
                variant: 'danger',
            );
        }
    }

    public function mount()
    {
        $this->nome = auth()->user()->name;
        $this->email = auth()->user()->email;
        $this->fone = auth()->user()->fone;
        $this->foto = auth()->user()->foto;

        $this->usuarioModulos = UserModules::where('user_id', auth()->user()->id)
            ->where('responsavel', true)
            ->pluck('id');
    }

    #[Computed]
    public function modulos()
    {
        return Module::all();
    }

    public function render()
    {
        return view('livewire.editar-usuario');
    }
}
