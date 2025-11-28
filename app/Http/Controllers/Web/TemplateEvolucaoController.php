<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TemplateEvolucao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplateEvolucaoController extends Controller
{
    public function index(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) ($request->query('per_page', 25));
        if (!in_array($perPage, $allowedPerPage, true)) { $perPage = 25; }
        $itens = TemplateEvolucao::orderBy('titulo')->paginate($perPage)->appends($request->query());
        return view('sistema-parametros.templates-evolucao', compact('itens','perPage','allowedPerPage'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'ativo' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return redirect()->route('sistema-parametros.templates-evolucao')
                ->withErrors($validator)->withInput();
        }
        $dados = $validator->validated();
        $dados['ativo'] = (bool) ($request->input('ativo', false));
        TemplateEvolucao::create($dados);
        return redirect()->route('sistema-parametros.templates-evolucao', $request->only(['per_page','page']))
            ->with('success','Template criado com sucesso');
    }

    public function update(Request $request, int $id)
    {
        $template = TemplateEvolucao::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'ativo' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return redirect()->route('sistema-parametros.templates-evolucao')
                ->withErrors($validator)->withInput();
        }
        $dados = $validator->validated();
        $dados['ativo'] = (bool) ($request->input('ativo', false));
        $template->update($dados);
        return redirect()->route('sistema-parametros.templates-evolucao', $request->only(['per_page','page']))
            ->with('success','Template atualizado com sucesso');
    }

    public function destroy(Request $request, int $id)
    {
        $template = TemplateEvolucao::findOrFail($id);
        $template->delete();
        return redirect()->route('sistema-parametros.templates-evolucao', $request->only(['per_page','page']))
            ->with('success','Template removido com sucesso');
    }
}












