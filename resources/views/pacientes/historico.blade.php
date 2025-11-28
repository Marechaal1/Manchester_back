@extends('layouts.app')

@section('title', 'Histórico de Triagens')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Histórico de Triagens - {{ $paciente->nome_completo }}</h3>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Classificação</th>
                                    <th>Status</th>
                                    <th>Tempo de Espera (min)</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($triagens as $t)
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
                                                ];
                                                $status = $t->status ?? null;
                                                $statusFormatado = $statusMap[$status] ?? str_replace('_', ' ', ucwords(strtolower($status ?? '')));
                                            @endphp
                                            {{ $statusFormatado ?: '-' }}
                                        </td>
                                        <td>{{ $t->tempo_espera_minutos ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('pacientes.triagens.show', [$paciente, $t]) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Ver detalhes
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhuma triagem encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $triagens->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


