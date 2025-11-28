<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticoCipe;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DiagnosticoCipeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $ativos = filter_var($request->query('ativos', 'true'), FILTER_VALIDATE_BOOLEAN);
        $query = $ativos ? DiagnosticoCipe::ativos() : DiagnosticoCipe::query();
        $diagnosticos = $query->orderBy('titulo')->get();

        return response()->json([
            'sucesso' => true,
            'dados' => $diagnosticos,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:50',
            'titulo' => 'required|string|max:255',
            'definicao' => 'nullable|string',
            'dominio' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'ativo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Dados inválidos',
                'erros' => $validator->errors(),
            ], 422);
        }

        $diagnostico = DiagnosticoCipe::create($validator->validated());

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Diagnóstico criado com sucesso',
            'dados' => $diagnostico,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $diagnostico = DiagnosticoCipe::find($id);
        if (!$diagnostico) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Diagnóstico não encontrado',
            ], 404);
        }

        return response()->json([
            'sucesso' => true,
            'dados' => $diagnostico,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $diagnostico = DiagnosticoCipe::find($id);
        if (!$diagnostico) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Diagnóstico não encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:50',
            'titulo' => 'nullable|string|max:255',
            'definicao' => 'nullable|string',
            'dominio' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'ativo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Dados inválidos',
                'erros' => $validator->errors(),
            ], 422);
        }

        $diagnostico->update($validator->validated());

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Diagnóstico atualizado com sucesso',
            'dados' => $diagnostico,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $diagnostico = DiagnosticoCipe::find($id);
        if (!$diagnostico) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Diagnóstico não encontrado',
            ], 404);
        }

        $diagnostico->delete();

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Diagnóstico removido com sucesso',
        ]);
    }
}












