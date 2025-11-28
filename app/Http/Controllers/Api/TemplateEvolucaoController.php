<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TemplateEvolucao;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TemplateEvolucaoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $ativos = filter_var($request->query('ativos', 'true'), FILTER_VALIDATE_BOOLEAN);
        $query = $ativos ? TemplateEvolucao::ativos() : TemplateEvolucao::query();
        $templates = $query->orderBy('titulo')->get();
        return response()->json(['sucesso' => true, 'dados' => $templates]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'ativo' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['sucesso'=>false,'mensagem'=>'Dados inválidos','erros'=>$validator->errors()],422);
        }
        $dados = $validator->validated();
        $template = TemplateEvolucao::create($dados);
        return response()->json(['sucesso'=>true,'dados'=>$template],201);
    }

    public function show(int $id): JsonResponse
    {
        $template = TemplateEvolucao::find($id);
        if (!$template) return response()->json(['sucesso'=>false,'mensagem'=>'Não encontrado'],404);
        return response()->json(['sucesso'=>true,'dados'=>$template]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $template = TemplateEvolucao::find($id);
        if (!$template) return response()->json(['sucesso'=>false,'mensagem'=>'Não encontrado'],404);
        $validator = Validator::make($request->all(), [
            'titulo' => 'nullable|string|max:255',
            'conteudo' => 'nullable|string',
            'ativo' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['sucesso'=>false,'mensagem'=>'Dados inválidos','erros'=>$validator->errors()],422);
        }
        $template->update($validator->validated());
        return response()->json(['sucesso'=>true,'dados'=>$template]);
    }

    public function destroy(int $id): JsonResponse
    {
        $template = TemplateEvolucao::find($id);
        if (!$template) return response()->json(['sucesso'=>false,'mensagem'=>'Não encontrado'],404);
        $template->delete();
        return response()->json(['sucesso'=>true,'mensagem'=>'Removido']);
    }
}












