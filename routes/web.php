<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::middleware(['guest'])->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/logout', [\App\Livewire\Auth\Login::class, 'logout'])->name('logout');

    Route::get('/', \App\Livewire\Index::class);
    Route::get('/sentinela/{modulo}', \App\Livewire\Consulta\MostrarConsulta::class)->name('estoque.index');
    Route::get('/relatorio/{tabela}', \App\Livewire\Relatorio::class)->lazy()->name('relatorio');

    Route::get('/usuario', \App\Livewire\EditarUsuario::class)->name('editar.usuario');
    Route::get('/criar', \App\Livewire\Consulta\CriarConsulta::class)->name('criar.consulta');
});

Route::get('/teste/{modulo}', function ($modulo) {
    /*Resumo Semanal Segunda*/
    /*$values = \App\Models\ValueTotal::join('queries', 'queries.id', '=', 'value_totals.query_id')
        ->select(['queries.titulo', 'value_totals.*'])
        ->whereDate('value_totals.created_at', '>=', now()->subDays(7))
        ->get();*/

    /*Criticos diarios 12:00H */
    /*$values = \App\Models\ValueTotal::join('queries', 'queries.id', '=', 'value_totals.query_id')
        ->select(['queries.titulo', 'queries.qtde_critica', 'value_totals.*'])
        ->whereDate('value_totals.created_at', '>=', \Carbon\Carbon::yesterday())
        ->whereRaw('value_totals.total >= queries.qtde_critica')
        ->where('queries.modulo', 1) // Assuming '1' is the ID of the module you want to filter
        ->get();

    return view('emails.critico-metrica', compact('values'));*/

    try {
        (new \App\Services\DispararEmail())->emailCritico(\App\Models\Module::find($modulo));

        dump('Email disparado com sucesso!');
    } catch (Exception $e) {
        dd($e->getMessage());
    }
});