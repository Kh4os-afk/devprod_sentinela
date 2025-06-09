<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Flux;

class Login extends Component
{
    #[Rule('required|email')]
    public $email;
    #[Rule('required')]
    public $password;
    public bool $invalido = false;

    public function login()
    {
        (empty($this->email) or empty($this->password)) ? $this->invalido = true : $this->invalido = false;

        $credentials =  $this->validate();

        if (Auth::attempt($credentials)) {
            session()->regenerate();

            return redirect()->intended();
        }

        $this->invalido = true;

        Flux::toast(
            heading: 'Erro',
            text: 'Credenciais inválidas.',
            variant: 'danger'
        );
    }

    public function logout()
    {
        Auth::logout();

        session()->invalidate();

        session()->regenerateToken();

        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth')
            ->title('Login');
    }
}
