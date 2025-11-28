<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SistemaParametrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parametros = [
            [
                'categoria_risco' => 'red',
                'nome_categoria' => 'Emergência',
                'tempo_reavaliacao_minutos' => 15,
                'descricao' => 'Pacientes com risco imediato de vida. Necessitam de atendimento imediato.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_risco' => 'orange',
                'nome_categoria' => 'Muito Urgente',
                'tempo_reavaliacao_minutos' => 30,
                'descricao' => 'Pacientes com risco potencial de vida. Atendimento em até 30 minutos.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_risco' => 'yellow',
                'nome_categoria' => 'Urgente',
                'tempo_reavaliacao_minutos' => 60,
                'descricao' => 'Pacientes com risco moderado. Atendimento em até 1 hora.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_risco' => 'green',
                'nome_categoria' => 'Pouco Urgente',
                'tempo_reavaliacao_minutos' => 120,
                'descricao' => 'Pacientes com risco baixo. Atendimento em até 2 horas.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_risco' => 'blue',
                'nome_categoria' => 'Não Urgente',
                'tempo_reavaliacao_minutos' => 240,
                'descricao' => 'Pacientes sem urgência. Atendimento em até 4 horas.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('sistema_parametros')->insert($parametros);
    }
}
