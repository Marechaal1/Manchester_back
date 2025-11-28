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
            $table->string('sobrenome', 255)->nullable()->after('nome_completo');
            $table->date('data_nascimento')->nullable()->after('sobrenome');
            $table->enum('sexo', ['MASCULINO', 'FEMININO', 'OUTRO'])->nullable()->after('data_nascimento');
            $table->enum('estado_civil', ['SOLTEIRO', 'CASADO', 'DIVORCIADO', 'VIUVO', 'UNIAO_ESTAVEL'])->nullable()->after('sexo');
            $table->string('cep', 10)->nullable()->after('estado_civil');
            $table->string('endereco', 255)->nullable()->after('cep');
            $table->string('numero', 20)->nullable()->after('endereco');
            $table->string('complemento', 100)->nullable()->after('numero');
            $table->string('bairro', 100)->nullable()->after('complemento');
            $table->string('cidade', 100)->nullable()->after('bairro');
            $table->string('estado', 2)->nullable()->after('cidade');
            $table->string('celular', 20)->nullable()->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'sobrenome',
                'data_nascimento',
                'sexo',
                'estado_civil',
                'cep',
                'endereco',
                'numero',
                'complemento',
                'bairro',
                'cidade',
                'estado',
                'celular'
            ]);
        });
    }
};
