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
        Schema::create('sistema_parametros', function (Blueprint $table) {
            $table->id();
            $table->string('categoria_risco')->unique(); // red, orange, yellow, green, blue
            $table->string('nome_categoria'); // Emergência, Muito Urgente, etc.
            $table->integer('tempo_reavaliacao_minutos'); // Tempo em minutos
            $table->text('descricao')->nullable(); // Descrição da categoria
            $table->boolean('ativo')->default(true); // Se a categoria está ativa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sistema_parametros');
    }
};
