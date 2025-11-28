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
        // Verificar se a coluna já existe (para evitar erro em re-execução)
        if (!Schema::hasColumn('users', 'coren')) {
            Schema::table('users', function (Blueprint $table) {
                // Adicionar coren após email (coluna que sempre existe na tabela base)
                // Se crm existir quando esta migration rodar, será movida depois
                $table->string('coren', 20)->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('coren');
        });
    }
};




