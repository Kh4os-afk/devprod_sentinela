<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWhatsappPromptToQueriesTable extends Migration
{
    public function up(): void
    {
        Schema::table('queries', function (Blueprint $table) {
            $table->text('whatsapp_prompt')->nullable()->after('whatsapp');
        });
    }
}
