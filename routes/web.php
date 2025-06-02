<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::middleware(['guest'])->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/logout', [\App\Livewire\Auth\Login::class, 'logout'])->name('logout');

    Route::get('/',\App\Livewire\Index::class);
    Route::get('/sentinela/{modulo}', \App\Livewire\Consulta\MostrarConsulta::class)->name('estoque.index');
    Route::get('/relatorio/{tabela}', \App\Livewire\Relatorio::class)->lazy()->name('relatorio');

    /*Route::get('/usuario', \App\Livewire\EditarUsuario::class)->name('editar.usuario');*/
    /*admin*/
    Route::get('/criar', \App\Livewire\Consulta\CriarConsulta::class)->name('criar.consulta');
    Route::get('/submodulo/criar', \App\Livewire\CriarSubModulo::class)->name('criar.submodulo');
});

Route::get('/teste',\App\Livewire\Teste::class);