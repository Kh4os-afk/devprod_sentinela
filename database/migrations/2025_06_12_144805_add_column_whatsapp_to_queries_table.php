<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWhatsappToQueriesTable extends Migration
{
    public function up(): void
    {
        Schema::table('queries', function (Blueprint $table) {
            $table->boolean('whatsapp')->default(false)->after('externo');
        });
    }
}
