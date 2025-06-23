<?php

namespace App\Services;

use App\Mail\CriticoMetricaMail;
use App\Mail\SitefMail;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DispararEmail
{
    public function emailCritico(Module $module)
    {
        $values = \App\Models\ValueTotal::join('queries', 'queries.id', '=', 'value_totals.query_id')
            ->select(['queries.titulo', 'queries.qtde_critica', 'value_totals.*'])
            ->whereDate('value_totals.created_at', '>=', \Carbon\Carbon::yesterday())
            ->whereRaw('value_totals.total >= queries.qtde_critica')
            ->where('queries.modulo', $module->id)
            ->get();

        $valuesGrafico = \App\Models\ValueTotal::join('queries', 'queries.id', '=', 'value_totals.query_id')
            ->select(['queries.titulo', 'queries.qtde_critica', 'value_totals.*'])
            ->whereDate('value_totals.created_at', '>=', today()->subDays(7))
            ->whereRaw('value_totals.total >= queries.qtde_critica')
            ->where('queries.modulo', $module->id)
            ->whereIn('queries.id', $values->pluck('query_id'))
            ->get();

        if ($values->isNotEmpty()) {
            $user_ids = \App\Models\UserModules::where('module_id', $module->id)
                ->where('responsavel', true)
                ->pluck('user_id')
                ->unique();

            if ($user_ids) {
                $users = \App\Models\User::whereIn('id', $user_ids)
                    ->where('notificacao', true)
                    ->pluck('email')
                    ->toArray();

                Mail::to($users)->queue(new CriticoMetricaMail($module->modulo, $values, $valuesGrafico));

                Log::info('DispararEmail: emailCritico metodo chamado', [
                    'Modulo' => $module,
                    'Usuarios' => $user_ids,
                    'Values' => $values,
                ]);
            } else {
                Log::info('DispararEmail: emailCritico - Nenhum usuário encontrado para o módulo', [
                    'Modulo' => $module,
                ]);
            }
        }
    }
}