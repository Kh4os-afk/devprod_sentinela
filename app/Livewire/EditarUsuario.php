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
    public $usuarioPermissao;
    public $foto;
    public $notificacao;

    public function salvar()
    {
        try {
            $data = [
                'name' => $this->nome,
                'email' => $this->email,
                'fone' => $this->fone,
            ];

            if ($this->foto) {
                $data['foto'] = $this->foto->store('fotos', 'public');
            }

            auth()->user()->update($data);

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
                ->whereIn('module_id', $this->usuarioModulos)
                ->update(['responsavel' => true]);
            UserModules::where('user_id', auth()->user()->id)
                ->whereNotIn('module_id', $this->usuarioModulos)
                ->update(['responsavel' => false]);

            auth()->user()->update([
                'notificacao' => $this->notificacao,
            ]);

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
        $this->notificacao = auth()->user()->notificacao;

        $this->usuarioModulos = UserModules::where('user_id', auth()->user()->id)
            ->where('responsavel', true)
            ->pluck('module_id');

        $this->usuarioPermissao = UserModules::where('user_id', auth()->user()->id)
            ->pluck('module_id');
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
