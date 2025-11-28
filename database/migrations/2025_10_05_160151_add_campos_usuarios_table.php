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
            $table->string('nome_completo', 255)->after('id');
            $table->string('cpf', 14)->unique()->after('nome_completo');
            $table->string('telefone', 20)->nullable()->after('cpf');
            $table->string('crm', 20)->nullable()->after('telefone'); // CRM para profissionais de saúde
            $table->enum('tipo_usuario', ['ENFERMEIRO', 'MEDICO', 'TECNICO_ENFERMAGEM', 'ADMINISTRADOR'])->after('crm');
            $table->boolean('ativo')->default(true)->after('tipo_usuario');
            $table->timestamp('ultimo_acesso')->nullable()->after('ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nome_completo',
                'cpf',
                'telefone',
                'crm',
                'tipo_usuario',
                'ativo',
                'ultimo_acesso'
            ]);
        });
    }
};
