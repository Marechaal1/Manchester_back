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
        Schema::create('usuario_perfil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('perfil_id')->constrained('perfis')->onDelete('cascade');
            $table->timestamp('data_atribuicao');
            $table->timestamp('data_remocao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            
            $table->unique(['usuario_id', 'perfil_id', 'ativo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_perfil');
    }
};
