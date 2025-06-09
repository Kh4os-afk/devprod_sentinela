<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponsavelToUserModulesTable extends Migration
{
    public function up(): void
    {
        Schema::table('user_modules', function (Blueprint $table) {
            $table->boolean('responsavel')->default(false)->after('module_id');
        });
    }
}
