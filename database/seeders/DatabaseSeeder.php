<?php

namespace Database\Seeders;

use App\Models\Horario;
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
        \App\Models\User::firstOrCreate([
            'email' => 'ti@barataodacarne.com.br',
        ], [
            'name' => 'Administrador',
            'is_admin' => 1,
            'email_verified_at' => now(),
            'password' => Hash::make('admb4r4t40**'),
            'remember_token' => Str::random(10),
        ]);

        $horario = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];
        foreach ($horario as $hora) {
            Horario::firstOrCreate(['horario' => $hora], ['ativo' => true]);
        }

        \App\Models\Module::firstOrCreate([
            'id' => '1',
        ], [
            'modulo' => 'Estoque',
            'icon' => 'truck',
            'extra' => false,
        ]);

        \App\Models\Module::firstOrCreate([
            'id' => '2',
        ], [
            'modulo' => 'Cadastro',
            'icon' => 'cube',
            'extra' => false,
        ]);

        \App\Models\Module::firstOrCreate([
            'id' => '3',
        ], [
            'modulo' => 'Comercial',
            'icon' => 'clipboard-document-list',
            'extra' => false,

        ]);

        \App\Models\Module::firstOrCreate([
            'id' => '4',
        ], [
            'modulo' => 'Compras',
            'icon' => 'calculator',
            'extra' => false,
        ]);

        \App\Models\Module::firstOrCreate([
            'id' => '5',
        ], [
            'modulo' => 'Contabilidade',
            'icon' => 'building-library',
            'extra' => false,
        ]);

        \App\Models\Module::firstOrCreate([
            'id' => '6',
        ], [
            'modulo' => 'Financeiro',
            'icon' => 'banknotes',
            'extra' => false,
        ]);

        \App\Models\Module::firstOrCreate([
            'id' => '7',
        ], [
            'modulo' => 'Fiscal',
            'icon' => 'presentation-chart-bar',
            'extra' => false,
        ]);

        \App\Models\Module::firstOrCreate([
            'id' => '8',
        ], [
            'modulo' => 'Parametrização',
            'icon' => 'identification',
            'extra' => false,
        ]);

        \App\Models\Module::firstOrCreate([
            'id' => '9',
        ], [
            'modulo' => 'TI',
            'icon' => 'cpu-chip',
            'extra' => false,
        ]);

        \App\Models\Module::firstOrCreate([
            'id' => '10',
        ], [
            'modulo' => 'Verbas',
            'icon' => 'bars-arrow-down',
            'extra' => false,
        ]);
    }
}
