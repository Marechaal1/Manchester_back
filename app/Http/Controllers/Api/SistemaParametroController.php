<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SistemaParametro;
use Illuminate\Http\JsonResponse;

class SistemaParametroController extends Controller
{
    /**
     * Obter todos os parâmetros ativos do sistema
     */
    public function index(): JsonResponse
    {
        try {
            $parametros = SistemaParametro::getAtivos();
            
            // Transformar para o formato esperado pelo frontend
            $parametrosFormatados = [];
            foreach ($parametros as $parametro) {
                $parametrosFormatados[$parametro->categoria_risco] = [
                    'tempo_minutos' => $parametro->tempo_reavaliacao_minutos,
                    'tempo_ms' => $parametro->tempo_reavaliacao_ms,
                    'nome' => $parametro->nome_categoria,
                    'descricao' => $parametro->descricao,
                    'ativo' => $parametro->ativo
                ];
            }
            
            return response()->json([
                'sucesso' => true,
                'dados' => $parametrosFormatados
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar parâmetros do sistema: ' . $e->getMessage());
            
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar parâmetros do sistema',
                'erro' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter parâmetro específico por categoria
     */
    public function show(string $categoria): JsonResponse
    {
        try {
            $parametro = SistemaParametro::getByCategoria($categoria);
            
            if (!$parametro) {
                return response()->json([
                    'sucesso' => false,
                    'mensagem' => 'Categoria de risco não encontrada'
                ], 404);
            }
            
            return response()->json([
                'sucesso' => true,
                'dados' => [
                    'categoria_risco' => $parametro->categoria_risco,
                    'tempo_minutos' => $parametro->tempo_reavaliacao_minutos,
                    'tempo_ms' => $parametro->tempo_reavaliacao_ms,
                    'nome' => $parametro->nome_categoria,
                    'descricao' => $parametro->descricao,
                    'ativo' => $parametro->ativo
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar parâmetro do sistema: ' . $e->getMessage());
            
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar parâmetro do sistema',
                'erro' => $e->getMessage()
            ], 500);
        }
    }
}
