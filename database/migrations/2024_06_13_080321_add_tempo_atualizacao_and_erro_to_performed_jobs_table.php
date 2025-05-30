<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('performed_jobs', function (Blueprint $table) {
            $table->text('erro')->after('id')->nullable();
            $table->integer('tempo_atualizacao')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('performed_jobs', function (Blueprint $table) {
            $table->dropColumn('erro');
            $table->dropColumn('tempo_atualizacao');
        });
    }
};
