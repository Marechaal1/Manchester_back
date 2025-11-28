@extends('layouts.app')

@section('title', 'Detalhes da Triagem')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Triagem de {{ $paciente->nome_completo }} - {{ $triagem->created_at?->format('d/m/Y H:i') }}</h3>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pacientes.historico', $paciente) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar ao histórico
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $normalize = function ($key) {
                            $key = is_string($key) ? $key : (string) $key;
                            $key = strtolower($key);
                            // remover espaços e caracteres não alfanuméricos
                            $key = preg_replace('/[^a-z0-9]+/i', '', $key);
                            return $key;
                        };
                        // Mapa de rótulos por chave normalizada
                        $labelMap = [
                            // gerais
                            'name' => 'Nome',
                            'category' => 'Categoria',
                            'date' => 'Data',
                            'descricao' => 'Descrição',
                            'valor' => 'Valor',
                            'value' => 'Valor',
                            'cid' => 'CID',
                            // sinais vitais
                            'bloodpressure' => 'Pressão arterial',
                            'heartrate' => 'Frequência cardíaca',
                            'respiratoryrate' => 'Frequência respiratória',
                            'temperature' => 'Temperatura',
                            'oxygensaturation' => 'Saturação de O₂',
                            'weight' => 'Peso',
                            'height' => 'Altura',
                            // atendimento médico - tempos
                            'inicioatendimento' => 'Início do atendimento',
                            'fimatendimento' => 'Fim do atendimento',
                            // exame físico
                            'headneck' => 'Cabeça e Pescoço',
                            'chest' => 'Tórax',
                            'abdomen' => 'Abdome',
                            'limbs' => 'Membros',
                            'neurological' => 'Neurológico',
                            'additional' => 'Adicionais',
                            // conduta
                            'decision' => 'Decisão',
                            'referral' => 'Encaminhamento',
                            'recommendations' => 'Recomendações',
                            'observations' => 'Observações',
                        ];
                        $translate = function ($key) use (&$labelMap, &$normalize) {
                            $n = $normalize($key);
                            return $labelMap[$n] ?? ucfirst(str_replace('_',' ', is_string($key) ? $key : (string) $key));
                        };
                        $renderValue = function ($val) use (&$translate) {
                            if (is_array($val)) {
                                if (\Illuminate\Support\Arr::isAssoc($val)) {
                                    $parts = [];
                                    foreach ($val as $k => $v) {
                                        if (is_scalar($v) || is_null($v)) {
                                            $parts[] = $translate($k) . ': ' . (is_bool($v) ? ($v ? 'Sim' : 'Não') : ($v ?? '-'));
                                        }
                                    }
                                    return implode('; ', array_filter($parts)) ?: 'Detalhes disponíveis';
                                }
                                $items = array_map(function ($x) {
                                    if (is_array($x)) {
                                        return $x['descricao'] ?? $x['nome'] ?? $x['name'] ?? 'Item';
                                    }
                                    return (string) $x;
                                }, $val);
                                return implode(', ', array_filter($items));
                            }
                            return is_bool($val) ? ($val ? 'Sim' : 'Não') : (string) ($val ?? '-');
                        };
                    @endphp
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Resumo</strong>
                                </div>
                                <div class="card-body">
                                    <p><strong>Data:</strong> {{ $triagem->created_at?->format('d/m/Y H:i') }}</p>
                                    <p><strong>Classificação:</strong>
                                        @php
                                            $descricaoRisco = [
                                                'VERMELHO' => 'Emergência',
                                                'LARANJA' => 'Muito Urgente',
                                                'AMARELO' => 'Urgente',
                                                'VERDE' => 'Pouco Urgente',
                                                'AZUL' => 'Não Urgente',
                                            ];
                                            $classificacao = $triagem->classificacao_risco ?? null;
                                            $descricao = $descricaoRisco[$classificacao] ?? $classificacao ?? '-';
                                        @endphp
                                        <span class="badge {{
                                            match($triagem->classificacao_risco) {
                                                'VERMELHO' => 'badge-vermelho',
                                                'LARANJA' => 'badge-laranja',
                                                'AMARELO' => 'badge-amarelo',
                                                'VERDE' => 'badge-verde',
                                                'AZUL' => 'badge-azul',
                                                default => 'bg-secondary'
                                            }
                                        }}">{{ $descricao }}</span>
                                    </p>
                                    <p><strong>Status:</strong> 
                                        @php
                                            $statusMap = [
                                                'EM_ANDAMENTO' => 'Em Andamento',
                                                'FINALIZADA' => 'Finalizada',
                                                'CANCELADA' => 'Cancelada',
                                                'AGUARDANDO' => 'Aguardando',
                                                'EM_OBSERVACAO' => 'Em Observação',
                                                'OBSERVACAO' => 'Observação',
                                            ];
                                            $status = $triagem->status ?? null;
                                            $statusFormatado = $statusMap[$status] ?? str_replace('_', ' ', ucwords(strtolower($status ?? '')));
                                        @endphp
                                        {{ $statusFormatado ?: '-' }}
                                    </p>
                                    <p><strong>Tempo de espera:</strong> {{ $triagem->tempo_espera_minutos ?? '-' }} min</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Dados Clínicos</strong>
                                </div>
                                <div class="card-body">
                                    @php $clinicos = $triagem->dados_clinicos ?? []; @endphp
                                    @if(is_array($clinicos) && !empty($clinicos))
                                        <div class="row">
                                            @foreach($clinicos as $chave => $valor)
                                                <div class="col-md-6 mb-2">
                                                    <div><strong>{{ $translate($chave) }}:</strong></div>
                                                    <div>
                                                        @if(is_array($valor))
                                                            <ul class="mb-0">
                                                                @foreach($valor as $item)
                                                                    <li>{{ is_array($item) ? ( $item['descricao'] ?? $item['name'] ?? 'Item' ) : (string) $item }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            {{ is_bool($valor) ? ($valor ? 'Sim' : 'Não') : (string) $valor }}
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <em>Nenhuma informação clínica registrada.</em>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong>SAE - Diagnósticos/Intervenções/Avaliação de Segurança</strong>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-primary">Diagnósticos de Enfermagem</h6>
                                        @php $diags = $triagem->diagnosticos_enfermagem ?? []; @endphp
                                        @if(is_array($diags) && count($diags))
                                            <ul class="mb-0">
                                                @foreach($diags as $d)
                                                    <li>{{ is_array($d) ? ($d['titulo'] ?? $renderValue($d)) : (string) $d }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <em>Não informado.</em>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <h6 class="text-primary">Intervenções de Enfermagem</h6>
                                        @php $interv = $triagem->intervencoes_enfermagem ?? []; @endphp
                                        @if(is_array($interv) && count($interv))
                                            <ul class="mb-0">
                                                @foreach($interv as $i)
                                                    <li>{{ is_array($i) ? ($i['titulo'] ?? $renderValue($i)) : (string) $i }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <em>Não informado.</em>
                                        @endif
                                    </div>
                                    <div class="mb-0">
                                        <h6 class="text-primary">Avaliação de Segurança</h6>
                                        @php $seg = $triagem->avaliacao_seguranca ?? []; @endphp
                                        @if(is_array($seg) && count($seg))
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <strong>Alergias</strong>
                                                    @if(!empty($seg['alergias']) && is_array($seg['alergias']))
                                                        <ul class="mb-0">
                                                            @foreach($seg['alergias'] as $a)
                                                                <li>{{ is_array($a) ? ($a['name'] ?? 'Alergia') : (string) $a }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div class="text-muted">-</div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>Comorbidades</strong>
                                                    @if(!empty($seg['comorbidades']) && is_array($seg['comorbidades']))
                                                        <ul class="mb-0">
                                                            @foreach($seg['comorbidades'] as $c)
                                                                <li>{{ is_array($c) ? ($c['name'] ?? 'Comorbidade') : (string) $c }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div class="text-muted">-</div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>Medicamentos crônicos</strong>
                                                    @if(!empty($seg['medicamentos_cronicos']) && is_array($seg['medicamentos_cronicos']))
                                                        <ul class="mb-0">
                                                            @foreach($seg['medicamentos_cronicos'] as $m)
                                                                <li>{{ is_array($m) ? ($m['name'] ?? 'Medicamento') : (string) $m }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div class="text-muted">-</div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>Pacientes de risco especial</strong>
                                                    @if(!empty($seg['pacientes_risco_especial']) && is_array($seg['pacientes_risco_especial']))
                                                        <ul class="mb-0">
                                                            @foreach($seg['pacientes_risco_especial'] as $p)
                                                                <li>{{ is_array($p) ? ($p['name'] ?? 'Risco especial') : (string) $p }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div class="text-muted">-</div>
                                                    @endif
                                                </div>
                                                <div class="col-12">
                                                    <strong>Observações adicionais</strong>
                                                    <div>
                                                        @php $obs = $seg['observacoes_adicionais'] ?? null; @endphp
                                                        {{ $renderValue($obs) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <em>Não informado.</em>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Atendimento Médico</strong>
                                </div>
                                <div class="card-body">
                                    @if($atendimento)
                                        <p><strong>Médico:</strong> {{ $atendimento->medico?->nome_completo ?? $atendimento->medico?->name ?? '-' }}</p>
                                        <p><strong>Início Atendimento:</strong> {{ $atendimento->inicio_atendimento?->format('d/m/Y H:i') ?? '-' }}</p>
                                        <p><strong>Fim Atendimento:</strong> {{ $atendimento->fim_atendimento?->format('d/m/Y H:i') ?? '-' }}</p>
                                        <p><strong>Status:</strong> 
                                            @php
                                                $statusMap = [
                                                    'EM_ANDAMENTO' => 'Em Andamento',
                                                    'FINALIZADA' => 'Finalizada',
                                                    'CANCELADA' => 'Cancelada',
                                                    'AGUARDANDO' => 'Aguardando',
                                                    'EM_OBSERVACAO' => 'Em Observação',
                                                    'OBSERVACAO' => 'Observação',
                                                ];
                                                $status = $atendimento->status ?? null;
                                                $statusFormatado = $statusMap[$status] ?? str_replace('_', ' ', ucwords(strtolower($status ?? '')));
                                            @endphp
                                            {{ $statusFormatado ?: '-' }}
                                        </p>

                                        <div class="mb-3">
                                            <h6 class="text-primary">Histórico Médico</h6>
                                            @php $hist = $atendimento->historico_medico; @endphp
                                            @if(is_array($hist))
                                                <ul class="mb-0">
                                                    @foreach($hist as $h)
                                                        <li>{{ $renderValue($h) }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div>{{ $hist ?? '-' }}</div>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-primary">Exame Físico</h6>
                                            @php $ef = $atendimento->exame_fisico ?? []; @endphp
                                            @if(is_array($ef) && count($ef))
                                                <dl class="row mb-0">
                                                    @foreach($ef as $k => $v)
                                                        <dt class="col-sm-5">{{ $translate($k) }}</dt>
                                                        <dd class="col-sm-7">{{ $renderValue($v) }}</dd>
                                                    @endforeach
                                                </dl>
                                            @else
                                                <em>Não informado.</em>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-primary">Diagnósticos</h6>
                                            @php $dms = $atendimento->diagnosticos ?? []; @endphp
                                            @if(is_array($dms) && count($dms))
                                                <ul class="mb-0">
                                                    @foreach($dms as $d)
                                                        @php
                                                            // Extrair apenas o texto do diagnóstico
                                                            $diagnosticoTexto = '';
                                                            if (is_array($d)) {
                                                                // Priorizar campos que contêm o texto do diagnóstico
                                                                $diagnosticoTexto = $d['diagnosis'] ?? $d['diagnostico'] ?? $d['descricao'] ?? $d['name'] ?? $d['titulo'] ?? '';
                                                            } else {
                                                                $diagnosticoTexto = (string) $d;
                                                            }
                                                        @endphp
                                                        <li>{{ $diagnosticoTexto ?: 'Diagnóstico não especificado' }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <em>Não informado.</em>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-primary">Exames Solicitados</h6>
                                            @php $exs = $atendimento->exames_solicitados ?? []; @endphp
                                            @if(is_array($exs) && count($exs))
                                                <ul class="mb-0">
                                                    @foreach($exs as $e)
                                                        <li>{{ $renderValue($e) }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <em>Não informado.</em>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-primary">Prescrições</h6>
                                            @php $prs = $atendimento->prescricoes ?? []; @endphp
                                            @if(is_array($prs) && count($prs))
                                                <ul class="mb-0">
                                                    @foreach($prs as $p)
                                                        <li>{{ $renderValue($p) }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <em>Não informado.</em>
                                            @endif
                                        </div>
                                        <div class="mb-0">
                                            <h6 class="text-primary">Conduta</h6>
                                            @php $cond = $atendimento->conduta ?? []; @endphp
                                            @if(is_array($cond) && count($cond))
                                                @php $isAssoc = \Illuminate\Support\Arr::isAssoc($cond); @endphp
                                                @if($isAssoc)
                                                    <dl class="row mb-0">
                                                        @foreach($cond as $k => $v)
                                                            <dt class="col-sm-5">{{ $translate($k) }}</dt>
                                                            <dd class="col-sm-7">{{ $renderValue($v) }}</dd>
                                                        @endforeach
                                                    </dl>
                                                @else
                                                    <ul class="mb-0">
                                                        @foreach($cond as $c)
                                                            <li>{{ $renderValue($c) }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @else
                                                <em>Não informado.</em>
                                            @endif
                                        </div>
                                    @else
                                        <em>Nenhum atendimento médico registrado para esta triagem.</em>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Reavaliações</strong>
                                </div>
                                <div class="card-body">
                                    @php $reavs = $triagem->reavaliacoes ?? []; @endphp
                                    @if(count($reavs))
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Data</th>
                                                        <th>Responsável</th>
                                                        <th>Observações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($reavs as $r)
                                                        <tr>
                                                            <td>{{ $r->created_at?->format('d/m/Y H:i') }}</td>
                                                            <td>{{ $r->usuario?->nome_completo ?? $r->usuario?->name ?? '-' }}</td>
                                                            <td>{{ $renderValue($r->observacoes) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <em>Nenhuma reavaliação registrada.</em>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
