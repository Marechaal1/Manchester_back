<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiagnosticosCipeSeeder extends Seeder
{
    public function run(): void
    {
        $itens = [
            ['codigo' => '00001', 'titulo' => 'Dor aguda', 'definicao' => 'Sensação desagradável, sensorial e emocional, associada a dano tecidual real ou potencial', 'dominio' => 'Conforto', 'categoria' => 'Dor', 'ativo' => true],
            ['codigo' => '00002', 'titulo' => 'Ansiedade', 'definicao' => 'Estado de inquietação, apreensão ou medo em resposta a uma ameaça real ou imaginária', 'dominio' => 'Coping/Estresse', 'categoria' => 'Respostas emocionais', 'ativo' => true],
            ['codigo' => '00003', 'titulo' => 'Déficit de autocuidado', 'definicao' => 'Incapacidade de realizar atividades de autocuidado necessárias para manter a saúde', 'dominio' => 'Atividade/Repouso', 'categoria' => 'Autocuidado', 'ativo' => true],
            ['codigo' => '00004', 'titulo' => 'Risco de infecção', 'definicao' => 'Vulnerabilidade aumentada para invasão por patógenos', 'dominio' => 'Segurança/Proteção', 'categoria' => 'Risco de infecção', 'ativo' => true],
            ['codigo' => '00005', 'titulo' => 'Déficit de conhecimento', 'definicao' => 'Ausência ou deficiência de informações cognitivas relacionadas a um tópico específico', 'dominio' => 'Conhecimento', 'categoria' => 'Conhecimento', 'ativo' => true],
            ['codigo' => '00006', 'titulo' => 'Padrão respiratório ineficaz', 'definicao' => 'Inspiração e/ou expiração que não proporciona ventilação adequada', 'dominio' => 'Respiração', 'categoria' => 'Ventilação', 'ativo' => true],
            ['codigo' => '00007', 'titulo' => 'Déficit de volume de líquidos', 'definicao' => 'Diminuição dos líquidos intravasculares, intersticiais e/ou intracelulares', 'dominio' => 'Nutrição', 'categoria' => 'Hidratação', 'ativo' => true],
            ['codigo' => '00008', 'titulo' => 'Mobilidade física prejudicada', 'definicao' => 'Limitação no movimento independente e intencional do corpo', 'dominio' => 'Atividade/Repouso', 'categoria' => 'Mobilidade', 'ativo' => true],
            ['codigo' => '00009', 'titulo' => 'Risco de quedas', 'definicao' => 'Vulnerabilidade aumentada para quedas que podem causar lesão física', 'dominio' => 'Segurança/Proteção', 'categoria' => 'Risco de lesão', 'ativo' => true],
            ['codigo' => '00010', 'titulo' => 'Padrão de sono perturbado', 'definicao' => 'Interrupção da quantidade e qualidade do sono', 'dominio' => 'Atividade/Repouso', 'categoria' => 'Repouso', 'ativo' => true],
            ['codigo' => '00011', 'titulo' => 'Integridade da pele prejudicada', 'definicao' => 'Alteração da epiderme e/ou derme', 'dominio' => 'Segurança/Proteção', 'categoria' => 'Integridade tecidual', 'ativo' => true],
            ['codigo' => '00012', 'titulo' => 'Constipação', 'definicao' => 'Eliminação intestinal infrequente ou difícil', 'dominio' => 'Eliminação', 'categoria' => 'Eliminação intestinal', 'ativo' => true],
            ['codigo' => '00013', 'titulo' => 'Fadiga', 'definicao' => 'Sensação de cansaço ou exaustão que interfere nas atividades', 'dominio' => 'Atividade/Repouso', 'categoria' => 'Energia', 'ativo' => true],
            ['codigo' => '00014', 'titulo' => 'Isolamento social', 'definicao' => 'Aloneness experimentado pelo indivíduo e percebido como imposto por outros', 'dominio' => 'Coping/Estresse', 'categoria' => 'Respostas emocionais', 'ativo' => true],
            ['codigo' => '00015', 'titulo' => 'Risco de aspiração', 'definicao' => 'Vulnerabilidade para entrada de secreções, sólidos ou líquidos nas vias aéreas', 'dominio' => 'Respiração', 'categoria' => 'Ventilação', 'ativo' => true],
        ];

        foreach ($itens as $item) {
            DB::table('diagnosticos_cipe')->updateOrInsert([
                'codigo' => $item['codigo'],
            ], $item + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}












