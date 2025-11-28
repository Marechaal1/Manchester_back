<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UsuarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\PacienteController;
use App\Http\Controllers\Web\SistemaParametroController;
use App\Http\Controllers\Web\DiagnosticoCipeController;
use App\Http\Controllers\Web\IntervencaoCipeController;
use App\Http\Controllers\Web\ResultadoNocController;
use App\Http\Controllers\Web\TemplateEvolucaoController;
use App\Http\Controllers\Web\RelatorioController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::get('/novo-usuario', [UsuarioController::class, 'create'])->name('usuarios.create.direct');
Route::post('/novo-usuario', [UsuarioController::class, 'store'])->name('usuarios.store.direct');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/dashboard/classificacao-risco', [DashboardController::class, 'classificacaoRisco'])
    ->name('dashboard.classificacao-risco')
    ->middleware('auth');

Route::middleware(['auth', 'nocache', 'tipo:MEDICO,ADMINISTRADOR'])->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    Route::put('/usuarios/{usuario}/ativar', [UsuarioController::class, 'ativar'])->name('usuarios.ativar');
    Route::put('/usuarios/{usuario}/inativar', [UsuarioController::class, 'inativar'])->name('usuarios.inativar');
});

Route::middleware(['auth', 'nocache', 'tipo:ENFERMEIRO,MEDICO,ADMINISTRADOR'])->group(function () {
    Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
    Route::get('/pacientes/create', [PacienteController::class, 'create'])->name('pacientes.create');
    Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::get('/pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show');
    Route::get('/pacientes/{paciente}/historico', [PacienteController::class, 'historico'])->name('pacientes.historico');
    Route::get('/pacientes/{paciente}/triagens/{triagem}', [PacienteController::class, 'triagem'])->name('pacientes.triagens.show');
    Route::get('/pacientes/{paciente}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
    Route::put('/pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update');
});


Route::middleware(['auth', 'nocache', 'tipo:MEDICO,ADMINISTRADOR'])->group(function () {
    Route::get('/sistema-parametros', [SistemaParametroController::class, 'index'])->name('sistema-parametros.index');
    Route::get('/sistema-parametros/tempos', [SistemaParametroController::class, 'tempos'])->name('sistema-parametros.tempos');
    Route::put('/sistema-parametros', [SistemaParametroController::class, 'update'])->name('sistema-parametros.update');
    Route::post('/sistema-parametros/reset', [SistemaParametroController::class, 'reset'])->name('sistema-parametros.reset');
    
    Route::get('/sistema-parametros/diagnosticos', [DiagnosticoCipeController::class, 'index'])->name('sistema-parametros.diagnosticos');
    Route::post('/sistema-parametros/diagnosticos', [DiagnosticoCipeController::class, 'store'])->name('sistema-parametros.diagnosticos.store');
    Route::put('/sistema-parametros/diagnosticos/{id}', [DiagnosticoCipeController::class, 'update'])->name('sistema-parametros.diagnosticos.update');
    Route::delete('/sistema-parametros/diagnosticos/{id}', [DiagnosticoCipeController::class, 'destroy'])->name('sistema-parametros.diagnosticos.destroy');

    Route::get('/sistema-parametros/intervencoes', [IntervencaoCipeController::class, 'index'])->name('sistema-parametros.intervencoes');
    Route::post('/sistema-parametros/intervencoes', [IntervencaoCipeController::class, 'store'])->name('sistema-parametros.intervencoes.store');
    Route::put('/sistema-parametros/intervencoes/{id}', [IntervencaoCipeController::class, 'update'])->name('sistema-parametros.intervencoes.update');
    Route::delete('/sistema-parametros/intervencoes/{id}', [IntervencaoCipeController::class, 'destroy'])->name('sistema-parametros.intervencoes.destroy');

    Route::get('/sistema-parametros/resultados', [ResultadoNocController::class, 'index'])->name('sistema-parametros.resultados');
    Route::post('/sistema-parametros/resultados', [ResultadoNocController::class, 'store'])->name('sistema-parametros.resultados.store');
    Route::put('/sistema-parametros/resultados/{id}', [ResultadoNocController::class, 'update'])->name('sistema-parametros.resultados.update');
    Route::delete('/sistema-parametros/resultados/{id}', [ResultadoNocController::class, 'destroy'])->name('sistema-parametros.resultados.destroy');

    Route::get('/sistema-parametros/templates-evolucao', [TemplateEvolucaoController::class, 'index'])->name('sistema-parametros.templates-evolucao');
    Route::post('/sistema-parametros/templates-evolucao', [TemplateEvolucaoController::class, 'store'])->name('sistema-parametros.templates-evolucao.store');
    Route::put('/sistema-parametros/templates-evolucao/{id}', [TemplateEvolucaoController::class, 'update'])->name('sistema-parametros.templates-evolucao.update');
    Route::delete('/sistema-parametros/templates-evolucao/{id}', [TemplateEvolucaoController::class, 'destroy'])->name('sistema-parametros.templates-evolucao.destroy');
});

Route::middleware(['auth', 'nocache', 'tipo:ENFERMEIRO,MEDICO,ADMINISTRADOR'])->group(function () {
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::post('/relatorios/triagens', [RelatorioController::class, 'triagens'])->name('relatorios.triagens');
    Route::post('/relatorios/atendimentos', [RelatorioController::class, 'atendimentos'])->name('relatorios.atendimentos');
});

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout')->middleware('auth');
