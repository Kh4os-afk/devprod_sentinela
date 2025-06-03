<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosTable extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->string('horario');
            $table->boolean('ativo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
}
