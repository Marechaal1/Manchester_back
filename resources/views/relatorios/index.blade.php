@extends('layouts.app')

@section('title', 'Relatórios')
@section('page-title', 'Relatórios')
@section('page-subtitle', 'Geração de relatórios em Excel')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-excel"></i>
                    Gerar Relatórios
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle"></i> Erro na geração do relatório:</h6>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row">
                    <!-- Relatório de Triagens -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-clipboard-list"></i>
                                    Relatório de Triagens
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('relatorios.triagens') }}" method="POST" id="formTriagens">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Filtrar por:</label>
                                        <select name="tipo_filtro" class="form-select" id="tipoFiltroTriagens" required>
                                            <option value="">Selecione...</option>
                                            <option value="periodo">Período</option>
                                            <option value="paciente">Paciente</option>
                                            <option value="enfermeiro">Enfermeiro</option>
                                        </select>
                                    </div>

                                    <!-- Filtro por Período -->
                                    <div id="filtroPeriodoTriagens" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Data Início</label>
                                            <input type="date" name="data_inicio" class="form-control" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Data Fim</label>
                                            <input type="date" name="data_fim" class="form-control" />
                                        </div>
                                    </div>

                                    <!-- Filtro por Paciente -->
                                    <div id="filtroPacienteTriagens" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Paciente</label>
                                            <select name="paciente_id" class="form-select">
                                                <option value="">Selecione um paciente...</option>
                                                @foreach($pacientes as $paciente)
                                                    <option value="{{ $paciente->id }}">{{ $paciente->nome_completo }} - {{ $paciente->cpf }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Filtro por Enfermeiro -->
                                    <div id="filtroEnfermeiroTriagens" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Enfermeiro</label>
                                            <select name="enfermeiro_id" class="form-select">
                                                <option value="">Selecione um enfermeiro...</option>
                                                @foreach($enfermeiros as $enfermeiro)
                                                    <option value="{{ $enfermeiro->id }}">{{ $enfermeiro->nome_completo ?? $enfermeiro->name }} - {{ $enfermeiro->coren ?? 'N/A' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-download"></i>
                                        Gerar Relatório Excel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Relatório de Atendimentos Médicos -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-user-md"></i>
                                    Relatório de Atendimentos Médicos
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('relatorios.atendimentos') }}" method="POST" id="formAtendimentos">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Filtrar por:</label>
                                        <select name="tipo_filtro" class="form-select" id="tipoFiltroAtendimentos" required>
                                            <option value="">Selecione...</option>
                                            <option value="periodo">Período</option>
                                            <option value="paciente">Paciente</option>
                                            <option value="medico">Médico</option>
                                        </select>
                                    </div>

                                    <!-- Filtro por Período -->
                                    <div id="filtroPeriodoAtendimentos" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Data Início</label>
                                            <input type="date" name="data_inicio" class="form-control" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Data Fim</label>
                                            <input type="date" name="data_fim" class="form-control" />
                                        </div>
                                    </div>

                                    <!-- Filtro por Paciente -->
                                    <div id="filtroPacienteAtendimentos" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Paciente</label>
                                            <select name="paciente_id" class="form-select">
                                                <option value="">Selecione um paciente...</option>
                                                @foreach($pacientes as $paciente)
                                                    <option value="{{ $paciente->id }}">{{ $paciente->nome_completo }} - {{ $paciente->cpf }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Filtro por Médico -->
                                    <div id="filtroMedicoAtendimentos" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Médico</label>
                                            <select name="medico_id" class="form-select">
                                                <option value="">Selecione um médico...</option>
                                                @foreach($medicos as $medico)
                                                    <option value="{{ $medico->id }}">{{ $medico->nome_completo ?? $medico->name }} - {{ $medico->crm ?? 'N/A' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-download"></i>
                                        Gerar Relatório Excel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Toggle filtros para Triagens
document.getElementById('tipoFiltroTriagens').addEventListener('change', function() {
    const tipo = this.value;
    const periodo = document.getElementById('filtroPeriodoTriagens');
    const paciente = document.getElementById('filtroPacienteTriagens');
    const enfermeiro = document.getElementById('filtroEnfermeiroTriagens');
    
    periodo.style.display = tipo === 'periodo' ? 'block' : 'none';
    paciente.style.display = tipo === 'paciente' ? 'block' : 'none';
    enfermeiro.style.display = tipo === 'enfermeiro' ? 'block' : 'none';
    
    // Limpar campos quando trocar
    if (tipo !== 'periodo') {
        periodo.querySelectorAll('input').forEach(input => input.value = '');
    }
    if (tipo !== 'paciente') {
        paciente.querySelectorAll('select').forEach(select => select.value = '');
    }
    if (tipo !== 'enfermeiro') {
        enfermeiro.querySelectorAll('select').forEach(select => select.value = '');
    }
});

// Toggle filtros para Atendimentos
document.getElementById('tipoFiltroAtendimentos').addEventListener('change', function() {
    const tipo = this.value;
    const periodo = document.getElementById('filtroPeriodoAtendimentos');
    const paciente = document.getElementById('filtroPacienteAtendimentos');
    const medico = document.getElementById('filtroMedicoAtendimentos');
    
    periodo.style.display = tipo === 'periodo' ? 'block' : 'none';
    paciente.style.display = tipo === 'paciente' ? 'block' : 'none';
    medico.style.display = tipo === 'medico' ? 'block' : 'none';
    
    // Limpar campos quando trocar
    if (tipo !== 'periodo') {
        periodo.querySelectorAll('input').forEach(input => input.value = '');
    }
    if (tipo !== 'paciente') {
        paciente.querySelectorAll('select').forEach(select => select.value = '');
    }
    if (tipo !== 'medico') {
        medico.querySelectorAll('select').forEach(select => select.value = '');
    }
});
</script>
@endsection

