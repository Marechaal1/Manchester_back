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
        Schema::create('diagnosticos_cipe', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable()->index();
            $table->string('titulo');
            $table->text('definicao')->nullable();
            $table->string('dominio')->nullable();
            $table->string('categoria')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosticos_cipe');
    }
};












