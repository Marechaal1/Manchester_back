<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'permite_extrair_relatorios')) {
                $table->boolean('permite_extrair_relatorios')->default(false)->after('permite_liberar_observacao');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'permite_extrair_relatorios')) {
                $table->dropColumn('permite_extrair_relatorios');
            }
        });
    }
};
