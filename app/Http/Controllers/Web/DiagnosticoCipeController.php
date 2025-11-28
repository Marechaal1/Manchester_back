<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticoCipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiagnosticoCipeController extends Controller
{
    public function index(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) ($request->query('per_page', 25));
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 25;
        }

        $diagnosticos = DiagnosticoCipe::orderBy('titulo')->paginate($perPage)->appends($request->query());

        return view('sistema-parametros.diagnosticos', compact('diagnosticos', 'perPage', 'allowedPerPage'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:50',
            'titulo' => 'required|string|max:255',
            'definicao' => 'nullable|string',
            'dominio' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'ativo' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('sistema-parametros.diagnosticos')
                ->withErrors($validator)
                ->withInput();
        }

        $dados = $validator->validated();
        $dados['ativo'] = (bool)($request->input('ativo', false));

        DiagnosticoCipe::create($dados);

        return redirect()->route('sistema-parametros.diagnosticos', $request->only(['per_page', 'page']))
            ->with('success', 'Diagnóstico criado com sucesso');
    }

    public function update(Request $request, int $id)
    {
        $diagnostico = DiagnosticoCipe::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:50',
            'titulo' => 'required|string|max:255',
            'definicao' => 'nullable|string',
            'dominio' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'ativo' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('sistema-parametros.diagnosticos')
                ->withErrors($validator)
                ->withInput();
        }

        $dados = $validator->validated();
        $dados['ativo'] = (bool)($request->input('ativo', false));

        $diagnostico->update($dados);

        return redirect()->route('sistema-parametros.diagnosticos', $request->only(['per_page', 'page']))
            ->with('success', 'Diagnóstico atualizado com sucesso');
    }

    public function destroy(Request $request, int $id)
    {
        $diagnostico = DiagnosticoCipe::findOrFail($id);
        $diagnostico->delete();

        return redirect()->route('sistema-parametros.diagnosticos', $request->only(['per_page', 'page']))
            ->with('success', 'Diagnóstico removido com sucesso');
    }
}
