<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('atendimentos_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('triagem_id')->nullable()->constrained('triagens');
            $table->foreignId('medico_id')->constrained('users');
            $table->text('historico_medico')->nullable();
            $table->json('exame_fisico')->nullable();
            $table->json('diagnosticos')->nullable();
            $table->json('exames_solicitados')->nullable();
            $table->json('prescricoes')->nullable();
            $table->json('conduta')->nullable();
            $table->enum('status', ['EM_ATENDIMENTO','OBSERVACAO','FINALIZADO'])->default('EM_ATENDIMENTO');
            $table->timestamp('inicio_atendimento')->nullable();
            $table->timestamp('fim_atendimento')->nullable();
            $table->timestamp('inicio_observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atendimentos_medicos');
    }
};



