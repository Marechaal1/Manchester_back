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
        Schema::create('sae', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('triagem_id')->nullable()->constrained('triagens');
            $table->foreignId('usuario_id')->constrained('users'); // Enfermeiro responsável
            $table->json('dados_clinicos')->nullable(); // Sinais vitais e dados clínicos
            $table->json('diagnosticos_enfermagem')->nullable(); // Diagnósticos CIPE
            $table->json('intervencoes_enfermagem')->nullable(); // Intervenções CIPE
            $table->text('evolucao_enfermagem')->nullable(); // Evolução de enfermagem
            $table->text('observacoes_adicionais')->nullable(); // Observações extras
            $table->string('coren')->nullable(); // COREN do enfermeiro
            $table->timestamp('data_registro');
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['paciente_id', 'data_registro']);
            $table->index(['triagem_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sae');
    }
};