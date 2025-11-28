<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Triagem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodo = $request->input('period', 'daily');

        // Define intervalo de datas conforme filtro selecionado
        [$inicio, $fim, $periodo] = $this->obterIntervaloDatas($periodo);

        $estatisticas = [
            'triagens_hoje' => Triagem::whereDate('data_triagem', today())->count(),
            'triagens_em_andamento' => Triagem::emAndamento()->count(),
            'classificacoes_risco' => Triagem::whereBetween('data_triagem', [$inicio, $fim])
                ->selectRaw('classificacao_risco, COUNT(*) as total')
                ->groupBy('classificacao_risco')
                ->orderByRaw("CASE 
                    WHEN classificacao_risco = 'VERMELHO' THEN 1
                    WHEN classificacao_risco = 'LARANJA' THEN 2
                    WHEN classificacao_risco = 'AMARELO' THEN 3
                    WHEN classificacao_risco = 'VERDE' THEN 4
                    WHEN classificacao_risco = 'AZUL' THEN 5
                    ELSE 6
                END")
                ->get()
        ];

        $triagens_recentes = Triagem::with('paciente')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $usuarios = User::query()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('estatisticas', 'triagens_recentes', 'usuarios', 'periodo'));
    }

    public function classificacaoRisco(Request $request)
    {
        $periodo = $request->input('period', 'daily');
        [$inicio, $fim, $periodo] = $this->obterIntervaloDatas($periodo);

        $dados = Triagem::whereBetween('data_triagem', [$inicio, $fim])
            ->selectRaw('classificacao_risco, COUNT(*) as total')
            ->groupBy('classificacao_risco')
            ->get();

        return response()->json($dados);
    }

    private function obterIntervaloDatas(string $periodo): array
    {
        switch ($periodo) {
            case 'weekly':
                $inicio = now()->startOfWeek();
                $fim = now()->endOfWeek();
                break;
            case 'monthly':
                $inicio = now()->startOfMonth();
                $fim = now()->endOfMonth();
                break;
            case 'yearly':
                $inicio = now()->startOfYear();
                $fim = now()->endOfYear();
                break;
            case 'daily':
            default:
                $inicio = now()->startOfDay();
                $fim = now()->endOfDay();
                $periodo = 'daily';
                break;
        }
        return [$inicio, $fim, $periodo];
    }
}
