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
        Schema::create('triagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->string('protocolo', 50)->unique(); // Número do protocolo da triagem
            $table->timestamp('data_triagem');
            $table->enum('classificacao_risco', ['VERMELHO', 'LARANJA', 'AMARELO', 'VERDE', 'AZUL']); // Protocolo Manchester
            $table->integer('tempo_espera_minutos')->nullable(); // Tempo de espera baseado na classificação
            $table->json('dados_clinicos')->nullable(); // Dados clínicos coletados
            $table->json('diagnosticos_enfermagem')->nullable(); // Diagnósticos de enfermagem
            $table->json('intervencoes_enfermagem')->nullable(); // Intervenções de enfermagem
            $table->json('avaliacao_seguranca')->nullable(); // Avaliação de segurança
            $table->text('observacoes')->nullable();
            $table->enum('status', ['EM_ANDAMENTO', 'CONCLUIDA', 'CANCELADA'])->default('EM_ANDAMENTO');
            $table->timestamp('data_conclusao')->nullable();
            $table->boolean('requer_reavaliacao')->default(false);
            $table->timestamp('data_reavaliacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triagens');
    }
};
