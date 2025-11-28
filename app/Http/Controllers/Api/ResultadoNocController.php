<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ResultadoNoc;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ResultadoNocController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $ativos = filter_var($request->query('ativos', 'true'), FILTER_VALIDATE_BOOLEAN);
        $query = $ativos ? ResultadoNoc::ativos() : ResultadoNoc::query();
        $itens = $query->orderBy('titulo')->get();
        return response()->json(['sucesso' => true, 'dados' => $itens]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:50',
            'titulo' => 'required|string|max:255',
            'definicao' => 'nullable|string',
            'dominio' => 'nullable|string|max:255',
            'ativo' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['sucesso' => false,'mensagem' => 'Dados inválidos','erros' => $validator->errors()], 422);
        }
        $item = ResultadoNoc::create($validator->validated());
        return response()->json(['sucesso' => true, 'dados' => $item], 201);
    }

    public function show(int $id): JsonResponse
    {
        $item = ResultadoNoc::find($id);
        if (!$item) return response()->json(['sucesso'=>false,'mensagem'=>'Não encontrado'],404);
        return response()->json(['sucesso'=>true,'dados'=>$item]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = ResultadoNoc::find($id);
        if (!$item) return response()->json(['sucesso'=>false,'mensagem'=>'Não encontrado'],404);
        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:50',
            'titulo' => 'nullable|string|max:255',
            'definicao' => 'nullable|string',
            'dominio' => 'nullable|string|max:255',
            'ativo' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['sucesso' => false,'mensagem' => 'Dados inválidos','erros' => $validator->errors()], 422);
        }
        $item->update($validator->validated());
        return response()->json(['sucesso'=>true,'dados'=>$item]);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = ResultadoNoc::find($id);
        if (!$item) return response()->json(['sucesso'=>false,'mensagem'=>'Não encontrado'],404);
        $item->delete();
        return response()->json(['sucesso'=>true,'mensagem'=>'Removido']);
    }
}












