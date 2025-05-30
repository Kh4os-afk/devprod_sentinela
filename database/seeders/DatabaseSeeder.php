<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Administrador',
            'email' => 'ti@barataodacarne.com.br',
            'is_admin' => 1,
            'email_verified_at' => now(),
            'password' => Hash::make('admb4r4t40**'),
            'remember_token' => Str::random(10),
        ]);

        \App\Models\Module::create([
            'id' => '1',
            'modulo' => 'Estoque',
            'icon' => 'truck',
            'extra' => false,
        ]);
        \App\Models\Module::create([
            'id' => '2',
            'modulo' => 'Cadastro',
            'icon' => 'cube',
            'extra' => false,

        ]);
        \App\Models\Module::create([
            'id' => '3',
            'modulo' => 'Comercial',
            'icon' => 'clipboard-document-list',
            'extra' => false,

        ]);
        \App\Models\Module::create([
            'id' => '4',
            'modulo' => 'Compras',
            'icon' => 'calculator',
            'extra' => false,

        ]);
        \App\Models\Module::create([
            'id' => '5',
            'modulo' => 'Contabilidade',
            'icon' => 'building-library',
            'extra' => false,

        ]);
        \App\Models\Module::create([
            'id' => '6',
            'modulo' => 'Financeiro',
            'icon' => 'banknotes',
            'extra' => false,

        ]);
        \App\Models\Module::create([
            'id' => '7',
            'modulo' => 'Fiscal',
            'icon' => 'presentation-chart-bar',
            'extra' => false,

        ]);
        \App\Models\Module::create([
            'id' => '8',
            'modulo' => 'Parametrização',
            'icon' => 'identification',
            'extra' => false,

        ]);
        \App\Models\Module::create([
            'id' => '9',
            'modulo' => 'TI',
            'icon' => 'cpu-chip',
            'extra' => false,

        ]);
        \App\Models\Module::create([
            'id' => '10',
            'modulo' => 'Verbas',
            'icon' => 'fa fa-industry',
            'extra' => false,

        ]);
    }
}
