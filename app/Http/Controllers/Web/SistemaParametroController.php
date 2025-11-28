<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SistemaParametro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SistemaParametroController extends Controller
{
    /**
     * Página inicial de Parâmetros do Sistema (cards dos módulos)
     */
    public function index()
    {
        return view('sistema-parametros.index');
    }

    /**
     * Página de configuração de tempos de reavaliação
     */
    public function tempos()
    {
        $parametros = SistemaParametro::orderBy('tempo_reavaliacao_minutos', 'asc')->get();
        return view('sistema-parametros.tempos', compact('parametros'));
    }

    /**
     * Atualizar parâmetros do sistema (tempos)
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parametros' => 'required|array',
            'parametros.*.id' => 'required|exists:sistema_parametros,id',
            'parametros.*.tempo_reavaliacao_minutos' => 'required|integer|min:1|max:1440', // Máximo 24 horas
            'parametros.*.nome_categoria' => 'required|string|max:255',
            'parametros.*.descricao' => 'nullable|string|max:1000',
            'parametros.*.ativo' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            foreach ($request->parametros as $parametroData) {
                $parametro = SistemaParametro::findOrFail($parametroData['id']);
                $parametro->update([
                    'tempo_reavaliacao_minutos' => $parametroData['tempo_reavaliacao_minutos'],
                    'nome_categoria' => $parametroData['nome_categoria'],
                    'descricao' => $parametroData['descricao'] ?? null,
                    'ativo' => (bool)($parametroData['ativo'] ?? false),
                ]);
            }

            return redirect()->route('sistema-parametros.tempos')
                ->with('success', 'Parâmetros do sistema atualizados com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar parâmetros: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Resetar parâmetros para valores padrão
     */
    public function reset()
    {
        try {
            // Deletar parâmetros existentes
            SistemaParametro::truncate();
            
            // Executar seeder para recriar valores padrão
            \Artisan::call('db:seed', ['--class' => 'SistemaParametrosSeeder']);
            
            return redirect()->route('sistema-parametros.tempos')
                ->with('success', 'Parâmetros resetados para valores padrão!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao resetar parâmetros: ' . $e->getMessage());
        }
    }
}
