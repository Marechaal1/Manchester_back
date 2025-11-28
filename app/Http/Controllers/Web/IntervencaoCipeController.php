<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\IntervencaoCipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IntervencaoCipeController extends Controller
{
    public function index(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) ($request->query('per_page', 25));
        if (!in_array($perPage, $allowedPerPage, true)) { $perPage = 25; }
        $itens = IntervencaoCipe::orderBy('titulo')->paginate($perPage)->appends($request->query());
        return view('sistema-parametros.intervencoes', compact('itens','perPage','allowedPerPage'));
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
            return redirect()->route('sistema-parametros.intervencoes')
                ->withErrors($validator)->withInput();
        }
        $dados = $validator->validated();
        $dados['ativo'] = (bool) ($request->input('ativo', false));
        IntervencaoCipe::create($dados);
        return redirect()->route('sistema-parametros.intervencoes', $request->only(['per_page','page']))
            ->with('success','Intervenção criada com sucesso');
    }

    public function update(Request $request, int $id)
    {
        $item = IntervencaoCipe::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:50',
            'titulo' => 'required|string|max:255',
            'definicao' => 'nullable|string',
            'dominio' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'ativo' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return redirect()->route('sistema-parametros.intervencoes')
                ->withErrors($validator)->withInput();
        }
        $dados = $validator->validated();
        $dados['ativo'] = (bool) ($request->input('ativo', false));
        $item->update($dados);
        return redirect()->route('sistema-parametros.intervencoes', $request->only(['per_page','page']))
            ->with('success','Intervenção atualizada com sucesso');
    }

    public function destroy(Request $request, int $id)
    {
        $item = IntervencaoCipe::findOrFail($id);
        $item->delete();
        return redirect()->route('sistema-parametros.intervencoes', $request->only(['per_page','page']))
            ->with('success','Intervenção removida com sucesso');
    }
}












