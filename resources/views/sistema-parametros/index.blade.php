@extends('layouts.app')

@section('title', 'Parâmetros do Sistema')
@section('page-title', 'Parâmetros do Sistema')
@section('page-subtitle', 'Selecione um módulo para configurar')

@section('content')
<div class="row g-3">
    <div class="col-md-6">
        <a href="{{ route('sistema-parametros.tempos') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3" style="font-size: 2rem; color: #0d6efd;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Configuração de Tempo de Reavaliação</h5>
                        <p class="card-text text-muted mb-0">Defina tempos por categoria de risco do Protocolo Manchester.</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('sistema-parametros.diagnosticos') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3" style="font-size: 2rem; color: #198754;">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Gerenciador de Diagnósticos (CIPE)</h5>
                        <p class="card-text text-muted mb-0">Cadastre, edite e remova diagnósticos utilizados no app.</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('sistema-parametros.intervencoes') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3" style="font-size: 2rem; color: #6610f2;">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Gerenciador de Intervenções (CIPE)</h5>
                        <p class="card-text text-muted mb-0">Gerencie as intervenções de enfermagem utilizadas no app.</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('sistema-parametros.resultados') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3" style="font-size: 2rem; color: #fd7e14;">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Gerenciador de Resultados (NOC)</h5>
                        <p class="card-text text-muted mb-0">Gerencie os resultados esperados utilizados no app.</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('sistema-parametros.templates-evolucao') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3" style="font-size: 2rem; color: #20c997;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Templates de Evolução</h5>
                        <p class="card-text text-muted mb-0">Gerencie os modelos de texto usados na evolução de enfermagem.</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
