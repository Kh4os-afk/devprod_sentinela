<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DefaultDefaultToQueriesTable extends Migration
{
    public function up(): void
    {
        \App\Models\Query::whereNull('submodulo_id')->update(['submodulo_id' => 0]);
        Schema::table('queries', function (Blueprint $table) {
            $table->unsignedInteger('submodulo_id')->after('modulo')->default(0)->change();
        });
    }
}
