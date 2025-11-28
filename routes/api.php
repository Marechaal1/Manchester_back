<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\TriagemController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AtendimentoMedicoController;
use App\Http\Controllers\Api\SAEController;
use App\Http\Controllers\Api\SistemaParametroController;
use App\Http\Controllers\Api\DiagnosticoCipeController;
use App\Http\Controllers\Api\IntervencaoCipeController;
use App\Http\Controllers\Api\ResultadoNocController;
use App\Http\Controllers\Api\TemplateEvolucaoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rotas públicas (sem autenticação)
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas (requerem autenticação)
Route::middleware(['auth:sanctum'])->name('api.')->group(function () {
    
    // Autenticação
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::put('/perfil', [AuthController::class, 'atualizarPerfil'])->name('perfil');
    Route::put('/alterar-senha', [AuthController::class, 'alterarSenha'])->name('alterar-senha');
    
    // Pacientes
    Route::apiResource('pacientes', PacienteController::class)->names('api.pacientes');
    Route::get('/pacientes/buscar-cpf/{cpf}', [PacienteController::class, 'buscarPorCpf'])->name('pacientes.buscar-cpf');
    
    // Triagens
    Route::apiResource('triagens', TriagemController::class)->names('api.triagens');
    Route::get('/triagens-ativas', [TriagemController::class, 'triagensAtivas'])->name('triagens.ativas');
    Route::get('/pacientes/{id}/historico-triagens', [TriagemController::class, 'historicoTriagensPaciente'])->name('triagens.historico-paciente');
    Route::put('/triagens/{id}/concluir', [TriagemController::class, 'concluir'])->name('triagens.concluir');
    Route::post('/triagens/{id}/reavaliacao', [TriagemController::class, 'agendarReavaliacao'])->name('triagens.agendar-reavaliacao');
    Route::post('/triagens/{id}/reavaliacoes', [TriagemController::class, 'registrarReavaliacao'])->name('triagens.registrar-reavaliacao');
    
    // Atendimentos Médicos
    Route::apiResource('atendimentos-medicos', AtendimentoMedicoController::class)->only(['index','store','show','update'])->names('api.atendimentos-medicos');
    Route::put('/atendimentos-medicos/{id}/finalizar', [AtendimentoMedicoController::class, 'finalizar'])->name('atendimentos-medicos.finalizar');
    Route::post('/atendimentos-medicos/{id}/observar', [AtendimentoMedicoController::class, 'observar'])->name('atendimentos-medicos.observar');
    
    // SAE (Sistematização da Assistência de Enfermagem)
    Route::apiResource('sae', SAEController::class)->names('api.sae');
    Route::get('/sae/anterior', [SAEController::class, 'anterior'])->name('sae.anterior');
    
    // Catálogos CIPE/NOC
    Route::apiResource('diagnosticos-cipe', DiagnosticoCipeController::class)->only(['index','store','show','update','destroy'])->names('api.diagnosticos-cipe');
    Route::apiResource('intervencoes-cipe', IntervencaoCipeController::class)->only(['index','store','show','update','destroy'])->names('api.intervencoes-cipe');
    Route::apiResource('resultados-noc', ResultadoNocController::class)->only(['index','store','show','update','destroy'])->names('api.resultados-noc');
    
    // Templates de Evolução
    Route::apiResource('templates-evolucao', TemplateEvolucaoController::class)->only(['index','store','show','update','destroy'])->names('api.templates-evolucao');
    
    // Usuários (apenas para administradores)
    Route::middleware('can:gerenciar-usuarios')->group(function () {
        Route::apiResource('usuarios', UsuarioController::class)->names('api.usuarios');
        Route::put('/usuarios/{id}/ativar', [UsuarioController::class, 'ativar'])->name('usuarios.ativar');
        Route::put('/usuarios/{id}/inativar', [UsuarioController::class, 'inativar'])->name('usuarios.inativar');
    });
    
    // Dashboard e relatórios
    Route::get('/dashboard/estatisticas', function () {
        return response()->json([
            'sucesso' => true,
            'dados' => [
                'total_pacientes' => \App\Models\Paciente::ativos()->count(),
                'total_triagens_hoje' => \App\Models\Triagem::whereDate('data_triagem', today())->count(),
                'triagens_em_andamento' => \App\Models\Triagem::emAndamento()->count(),
                'classificacoes_risco' => \App\Models\Triagem::selectRaw('classificacao_risco, COUNT(*) as total')
                    ->groupBy('classificacao_risco')
                    ->get()
            ]
        ]);
    })->name('dashboard.estatisticas');
});

// Parâmetros do Sistema
Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::get('/sistema-parametros', [SistemaParametroController::class, 'index'])->name('sistema-parametros.index');
    Route::get('/sistema-parametros/{categoria}', [SistemaParametroController::class, 'show'])->name('sistema-parametros.show');
});
