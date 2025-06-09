<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtdeCriticaColumnToQueriesTable extends Migration
{
    public function up(): void
    {
        Schema::table('queries', function (Blueprint $table) {
            $table->unsignedInteger('qtde_critica')->after('horarios_execucao')->nullable();
        });
    }
}
