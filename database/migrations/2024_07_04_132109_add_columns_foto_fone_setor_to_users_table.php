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
        Schema::table('users', function (Blueprint $table) {
            $table->string('fone')->nullable()->after('is_admin');
            $table->string('setor')->nullable()->after('fone');
            $table->string('foto')->nullable()->after('setor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fone');
            $table->dropColumn('setor');
            $table->dropColumn('foto');
        });
    }
};
