@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Triagem')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Visão geral do sistema de triagem Manchester')

@section('content')
<div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted small mb-1">Triagens Hoje</div>
                    <div class="h4 mb-0 fw-bold" style="color: var(--cor-cinza-escuro);">
                        {{ $estatisticas['triagens_hoje'] }}
                    </div>
                </div>
                <div class="ms-3">
                    <i class="fas fa-clipboard-list fa-2x" style="color: var(--cor-sucesso);"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted small mb-1">Em Andamento</div>
                    <div class="h4 mb-0 fw-bold" style="color: var(--cor-cinza-escuro);">
                        {{ $estatisticas['triagens_em_andamento'] }}
                    </div>
                </div>
                <div class="ms-3">
                    <i class="fas fa-clock fa-2x" style="color: var(--cor-aviso);"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Classificações de Risco
                    </h5>
                    <form class="ms-auto d-flex align-items-center" onsubmit="return false;">
                        <label for="period" class="me-2 small" style="color: #ffffff;">Período</label>
                        <select id="period" name="period" class="form-select form-select-sm" style="color: #ffffff; background-color: transparent;">
                            <option value="daily" {{ ($periodo ?? 'daily') === 'daily' ? 'selected' : '' }}>Diário</option>
                            <option value="weekly" {{ ($periodo ?? 'daily') === 'weekly' ? 'selected' : '' }}>Semanal</option>
                            <option value="monthly" {{ ($periodo ?? 'daily') === 'monthly' ? 'selected' : '' }}>Mensal</option>
                            <option value="yearly" {{ ($periodo ?? 'daily') === 'yearly' ? 'selected' : '' }}>Anual</option>
                        </select>
                    </form>
                    <div class="ms-3">
                        <span class="small" style="color: #ffffff;">Total de Consultas:</span>
                        <span id="totalConsultas" class="fw-bold ms-1" style="color: #ffffff;">0</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="classificacaoChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Triagens Recentes
                </h5>
            </div>
            <div class="card-body">
                @if($triagens_recentes->count() > 0)
                    @php
                        $descricaoRisco = [
                            'VERMELHO' => 'Emergência',
                            'LARANJA' => 'Muito Urgente',
                            'AMARELO' => 'Urgente',
                            'VERDE'   => 'Pouco Urgente',
                            'AZUL'    => 'Não Urgente',
                        ];
                    @endphp
                    @foreach($triagens_recentes as $triagem)
                        <div class="d-flex align-items-center mb-3 p-3 rounded" style="background: var(--cor-cinza-claro);">
                            <div class="me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; background: var(--cor-primaria);">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-muted">{{ $triagem->created_at ? $triagem->created_at->copy()->setTimezone(config('app.timezone', 'America/Sao_Paulo'))->format('d/m/Y H:i') : '' }}</div>
                                <div class="fw-bold">{{ $triagem->paciente->nome_completo }}</div>
                                <span class="badge badge-{{ strtolower($triagem->classificacao_risco) }}">
                                    {{ $descricaoRisco[$triagem->classificacao_risco] ?? $triagem->classificacao_risco }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhuma triagem recente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    Usuários do Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Último Acesso</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                                <tr>
                                    <td class="fw-bold">{{ $usuario->nome_completo ?? $usuario->name }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        <span class="badge" style="background: var(--cor-primaria);">{{ $usuario->tipo_usuario }}</span>
                                    </td>
                                    <td>
                                        @if($usuario->ativo)
                                            <span class="badge" style="background: var(--cor-sucesso);">Ativo</span>
                                        @else
                                            <span class="badge" style="background: var(--cor-erro);">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($usuario->ultimo_acesso)
                                            {{ $usuario->ultimo_acesso->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">Nunca</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const ctx = document.getElementById('classificacaoChart').getContext('2d');
const classificacaoData = @json($estatisticas['classificacoes_risco']);
const dataUrl = '{{ route('dashboard.classificacao-risco') }}';

const ordemManchester = ['VERMELHO', 'LARANJA', 'AMARELO', 'VERDE', 'AZUL'];
const coresManchester = [
    '#dc3545',
    '#fd7e14',
    '#ffc107',
    '#198754',
    '#0d6efd'
];
const descricaoManchester = {
    'VERMELHO': 'Emergência',
    'LARANJA': 'Muito Urgente',
    'AMARELO': 'Urgente',
    'VERDE': 'Pouco Urgente',
    'AZUL': 'Não Urgente'
};

const labels = [];
const data = [];
const backgroundColor = [];

ordemManchester.forEach((classificacao, index) => {
    const item = classificacaoData.find(d => d.classificacao_risco === classificacao);
    if (item) {
        labels.push(descricaoManchester[classificacao] || classificacao);
        data.push(item.total);
        backgroundColor.push(coresManchester[index]);
    }
});

const classificacaoChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: backgroundColor,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 700,
            easing: 'easeInOutCubic'
        },
        plugins: {
            legend: {
                position: 'bottom',
                onClick: (e, legendItem, legend) => {
                    try {
                        const chart = legend.chart;
                        const index = legendItem.index;
                        if (typeof chart.toggleDataVisibility === 'function') {
                            chart.toggleDataVisibility(index);
                        } else {
                            const meta = chart.getDatasetMeta(0);
                            const arc = meta && meta.data ? meta.data[index] : null;
                            if (arc) arc.hidden = !arc.hidden;
                        }
                        chart.update();
                        atualizarTotalVisivel(chart);
                    } catch (_e) {}
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.raw || 0;
                        const chart = context.chart;
                        const dataset = chart.data.datasets[0];
                        const total = (dataset.data || []).reduce((sum, v, i) => {
                            const visivel = typeof chart.getDataVisibility === 'function'
                                ? chart.getDataVisibility(i)
                                : !(chart.getDatasetMeta(0).data[i] && chart.getDatasetMeta(0).data[i].hidden);
                            return visivel ? (sum + (Number(v) || 0)) : sum;
                        }, 0) || 1;
                        const pct = Math.round((value / total) * 100);
                        return value + ' (' + pct + '%)';
                    }
                }
            }
        }
    }
});

function atualizarTotalConsultas(valores) {
    try {
        const total = (valores || []).reduce((acc, v) => acc + (Number(v) || 0), 0);
        const el = document.getElementById('totalConsultas');
        if (el) el.textContent = String(total);
    } catch (_e) {}
}
function atualizarTotalVisivel(chart) {
    try {
        const ds = chart.data.datasets[0] || { data: [] };
        const total = (ds.data || []).reduce((acc, v, i) => {
            const visivel = typeof chart.getDataVisibility === 'function'
                ? chart.getDataVisibility(i)
                : !(chart.getDatasetMeta(0).data[i] && chart.getDatasetMeta(0).data[i].hidden);
            return visivel ? (acc + (Number(v) || 0)) : acc;
        }, 0);
        const el = document.getElementById('totalConsultas');
        if (el) el.textContent = String(total);
    } catch (_e) {}
}
atualizarTotalVisivel(classificacaoChart);

document.getElementById('period').addEventListener('change', async function (e) {
    const periodo = e.target.value;
    try {
        const res = await fetch(dataUrl + '?period=' + encodeURIComponent(periodo), {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('Falha ao buscar dados');
        const json = await res.json();

        const novosDados = [];
        const novasLabels = [];
        const novasCores = [];

        ordemManchester.forEach((classificacao, index) => {
            const item = json.find(d => d.classificacao_risco === classificacao);
            novasLabels.push(descricaoManchester[classificacao] || classificacao);
            novosDados.push(item ? item.total : 0);
            novasCores.push(coresManchester[index]);
        });

        classificacaoChart.data.labels = novasLabels;
        classificacaoChart.data.datasets[0].data = novosDados;
        classificacaoChart.data.datasets[0].backgroundColor = novasCores;
        try {
            if (typeof classificacaoChart.setDataVisibility === 'function') {
                novasLabels.forEach((_, i) => classificacaoChart.setDataVisibility(i, true));
            } else {
                const meta = classificacaoChart.getDatasetMeta(0);
                if (meta && meta.data) meta.data.forEach(arc => { arc.hidden = false; });
            }
        } catch (_e) {}
        classificacaoChart.update();

        atualizarTotalVisivel(classificacaoChart);

        const url = new URL(window.location.href);
        url.searchParams.set('period', periodo);
        window.history.replaceState({}, '', url);
    } catch (err) {
        console.error(err);
    }
});
</script>
@endsection
