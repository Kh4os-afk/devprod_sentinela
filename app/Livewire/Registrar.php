<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\UserModules;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Registrar extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name;
    public $email;
    public $is_admin = false;
    public $modulo = [];
    public $idParaDeletar;
    public $editar = false;


    public function rules()
    {
        return [
            'name' => 'required|min:3|max:80',
            'email' => 'required|email',
            'is_admin' => 'boolean',
            'modulo' => 'required',
        ];
    }

    public function update()
    {
        $this->validate();
    }

    public function submit()
    {
        $this->validate();
        try {
            $password = User::where('email', $this->email)->first();

            $user = User::updateOrCreate(['email' => $this->email], [
                'name' => $this->name,
                'is_admin' => $this->is_admin,
                'password' => $password ? $password->password : Hash::make('padrao'),
            ]);

            // Atualiza os módulos do usuário
            $userModulesIds = [];
            foreach ($this->modulo as $modulo) {
                $userModule = UserModules::updateOrCreate(['user_id' => $user->id, 'module_id' => $modulo], [
                    'add_by' => auth()->user()->id,
                ]);
                $userModulesIds[] = $userModule->id;
            }

            // Remove módulos que foram desmarcados pelo usuário
            UserModules::where('user_id', $user->id)
                ->whereNotIn('id', $userModulesIds)
                ->delete();

            $this->reset();

            Flux::toast(
                heading: 'Sucesso',
                text: 'Funcionario Cadastrado/Editado com Sucesso.',
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

    public function edit(User $id)
    {
        $this->editar = true;

        $this->name = $id->name;
        $this->email = $id->email;
        $this->is_admin = $id->is_admin;
        $this->modulo = $id->userModules->pluck('module_id')->toArray(); // IDs dos módulos do usuário

        $this->validate([
            'name' => 'required|min:3|max:80',
            'email' => 'required|email',
            'is_admin' => 'required|boolean',
        ]);

        Flux::toast(
            heading: 'Atenção',
            text: 'Edite o Usuário',
            variant: 'warning',
        );
    }

    public function delete(User $id)
    {
        $this->idParaDeletar = $id;
    }

    public function resetPassword($userId)
    {

    }


    public function confirmedResetPassword($data)
    {
        if ($data['userId']) {
            $user = User::find($data['userId']);
            if ($user) {
                $user->update(['password' => Hash::make('padrao')]);

                $this->alert('success', 'Senha do Usuário ' . $user->name . ' alterado para <b>padrao</b>', [
                    'timerProgressBar' => true,
                ]);
            }
        }
    }

    public function confirmed(): void
    {
        if ($this->idParaDeletar->is_admin) {
            $this->alert('error', 'Não é possível deletar um usuário Administrador', [
                'timerProgressBar' => true,
            ]);
        } else {
            try {
                $this->idParaDeletar->delete();

                $this->alert('success', 'Usuario Deletado Com Sucesso', [
                    'timerProgressBar' => true,
                ]);
            } catch (\Exception $e) {
                $this->alert('error', $e->getMessage());
            }
        }
    }

    public function resetar()
    {
        $this->reset();
    }

    public function render()
    {
       /*$this->modulo = auth()->user()->userModules()->pluck('module_id');*/

        return view('livewire.registrar', [
            'usuarios' => User::paginate(5),
        ]);
    }
}