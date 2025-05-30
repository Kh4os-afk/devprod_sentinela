<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubModulosTable extends Migration
{
    public function up(): void
    {
        Schema::create('sub_modulos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('modulo_id');
            $table->string('submodulo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_modulos');
    }
}
