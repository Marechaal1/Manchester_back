<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@sistema.com',
            'password' => bcrypt('123456'),
            'nome_completo' => 'Administrador do Sistema',
            'cpf' => '000.000.000-00',
            'telefone' => '(11) 99999-9999',
            'tipo_usuario' => 'ADMINISTRADOR',
            'ativo' => true,
        ]);

        // Criar usuário enfermeiro
        $enfermeiro = User::create([
            'name' => 'Enfermeiro',
            'email' => 'enfermeiro@sistema.com',
            'password' => bcrypt('123456'),
            'nome_completo' => 'Enfermeiro Chefe',
            'cpf' => '111.111.111-11',
            'telefone' => '(11) 88888-8888',
            'crm' => 'ENF-12345',
            'tipo_usuario' => 'ENFERMEIRO',
            'ativo' => true,
        ]);

        // Criar usuário médico
        $medico = User::create([
            'name' => 'Médico',
            'email' => 'medico@sistema.com',
            'password' => bcrypt('123456'),
            'nome_completo' => 'Médico Responsável',
            'cpf' => '222.222.222-22',
            'telefone' => '(11) 77777-7777',
            'crm' => 'CRM-12345',
            'tipo_usuario' => 'MEDICO',
            'ativo' => true,
        ]);

        // Criar perfis
        $perfilAdmin = \App\Models\Perfil::create([
            'nome_perfil' => 'Administrador',
            'descricao' => 'Acesso total ao sistema',
            'permissoes' => ['*'],
            'ativo' => true,
        ]);

        $perfilEnfermeiro = \App\Models\Perfil::create([
            'nome_perfil' => 'Enfermeiro',
            'descricao' => 'Acesso às funcionalidades de triagem',
            'permissoes' => ['triagem.*', 'paciente.*'],
            'ativo' => true,
        ]);

        $perfilMedico = \App\Models\Perfil::create([
            'nome_perfil' => 'Médico',
            'descricao' => 'Acesso ao atendimento médico e observação',
            'permissoes' => ['atendimento.*', 'paciente.*'],
            'ativo' => true,
        ]);

        // Atribuir perfis aos usuários
        $admin->perfis()->attach($perfilAdmin->id, [
            'data_atribuicao' => now(),
            'ativo' => true
        ]);

        $enfermeiro->perfis()->attach($perfilEnfermeiro->id, [
            'data_atribuicao' => now(),
            'ativo' => true
        ]);

        $medico->perfis()->attach($perfilMedico->id, [
            'data_atribuicao' => now(),
            'ativo' => true
        ]);

        // Criar alguns pacientes de exemplo
        \App\Models\Paciente::create([
            'nome_completo' => 'João Silva Santos',
            'cpf' => '123.456.789-00',
            'data_nascimento' => '1985-05-15',
            'sexo' => 'M',
            'telefone' => '(11) 99999-1111',
            'email' => 'joao@email.com',
            'endereco' => 'Rua das Flores, 123',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'cep' => '01234-567',
            'ativo' => true,
        ]);

        \App\Models\Paciente::create([
            'nome_completo' => 'Maria Oliveira Costa',
            'cpf' => '987.654.321-00',
            'data_nascimento' => '1990-08-22',
            'sexo' => 'F',
            'telefone' => '(11) 88888-2222',
            'email' => 'maria@email.com',
            'endereco' => 'Av. Paulista, 456',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'cep' => '01310-100',
            'ativo' => true,
        ]);

        // Parâmetros do sistema (tempos de reavaliação)
        $this->call(SistemaParametrosSeeder::class);

        // Diagnósticos CIPE iniciais
        $this->call(DiagnosticosCipeSeeder::class);
    }
}
