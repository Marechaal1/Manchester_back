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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo', 255);
            $table->string('cpf', 14)->unique();
            $table->date('data_nascimento');
            $table->enum('sexo', ['M', 'F', 'O']); // Masculino, Feminino, Outro
            $table->string('telefone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('endereco')->nullable();
            $table->string('cidade', 100)->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('nome_responsavel', 255)->nullable();
            $table->string('telefone_responsavel', 20)->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
