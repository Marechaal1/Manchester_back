@extends('layouts.app')

@section('title', 'Detalhes do Paciente')
@section('page-title', 'Detalhes do Paciente')
@section('page-subtitle', 'Informações do paciente')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $paciente->nome_completo }}</h3>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pacientes.historico', $paciente) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-clock-rotate-left"></i> Histórico
                        </a>
                        <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">Dados Cadastrais</h5>
                            <table class="table table-borderless">
                                <tr><td><strong>CPF:</strong></td><td>{{ $paciente->cpf }}</td></tr>
                                <tr><td><strong>Idade:</strong></td><td>{{ $paciente->idade ?? '-' }}</td></tr>
                                <tr><td><strong>Sexo:</strong></td><td>{{ $paciente->sexo ?? '-' }}</td></tr>
                                <tr><td><strong>Telefone:</strong></td><td>{{ $paciente->telefone ?? '-' }}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>{{ $paciente->email ?? '-' }}</td></tr>
                                <tr><td><strong>Endereço:</strong></td><td>{{ $paciente->endereco ?? '-' }}</td></tr>
                                <tr><td><strong>Cidade/Estado:</strong></td><td>{{ $paciente->cidade ?? '-' }} / {{ $paciente->estado ?? '-' }}</td></tr>
                                <tr><td><strong>CEP:</strong></td><td>{{ $paciente->cep ?? '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-primary">Responsável</h5>
                            <table class="table table-borderless">
                                <tr><td><strong>Nome:</strong></td><td>{{ $paciente->nome_responsavel ?? '-' }}</td></tr>
                                <tr><td><strong>Telefone:</strong></td><td>{{ $paciente->telefone_responsavel ?? '-' }}</td></tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h5 class="text-primary">Triagens</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Classificação</th>
                                    <th>Status</th>
                                    <th>Tempo de Espera (min)</th>
                                    <th>Início Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paciente->triagens as $t)
                                    <tr>
                                        <td>{{ $t->created_at?->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @php
                                                $descricaoRisco = [
                                                    'VERMELHO' => 'Emergência',
                                                    'LARANJA' => 'Muito Urgente',
                                                    'AMARELO' => 'Urgente',
                                                    'VERDE' => 'Pouco Urgente',
                                                    'AZUL' => 'Não Urgente',
                                                ];
                                                $classificacao = $t->classificacao_risco ?? null;
                                                $descricao = $descricaoRisco[$classificacao] ?? $classificacao ?? '-';
                                            @endphp
                                            <span class="badge {{
                                                match($t->classificacao_risco) {
                                                    'VERMELHO' => 'badge-vermelho',
                                                    'LARANJA' => 'badge-laranja',
                                                    'AMARELO' => 'badge-amarelo',
                                                    'VERDE' => 'badge-verde',
                                                    'AZUL' => 'badge-azul',
                                                    default => 'bg-secondary'
                                                }
                                            }}">
                                                {{ $descricao }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusMap = [
                                                    'EM_ANDAMENTO' => 'Em Andamento',
                                                    'FINALIZADA' => 'Finalizada',
                                                    'CANCELADA' => 'Cancelada',
                                                    'AGUARDANDO' => 'Aguardando',
                                                    'EM_OBSERVACAO' => 'Em Observação',
                                                    'OBSERVACAO' => 'Observação',
                                                ];
                                                $status = $t->status ?? null;
                                                $statusFormatado = $statusMap[$status] ?? str_replace('_', ' ', ucwords(strtolower($status ?? '')));
                                            @endphp
                                            {{ $statusFormatado ?: '-' }}
                                        </td>
                                        <td>
                                            @if(($t->status ?? '') === 'OBSERVACAO')
                                                -
                                            @else
                                                {{ $t->tempo_espera_minutos ?? '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            @php $inicioObs = optional($t->atendimentoMedico)->inicio_observacao; @endphp
                                            {{ $inicioObs ? $inicioObs->format('d/m/Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Nenhuma triagem encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

