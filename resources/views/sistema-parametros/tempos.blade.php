@extends('layouts.app')

@section('title', 'Parâmetros do Sistema — Tempos')
@section('page-title', 'Parâmetros do Sistema')
@section('page-subtitle', 'Configuração de Tempo de Reavaliação')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock"></i>
                    Configuração de Tempos de Reavaliação
                </h5>
                <p class="card-text mt-2 mb-0">
                    Configure os tempos de reavaliação para cada categoria de risco do Protocolo Manchester.
                </p>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle"></i> Erros encontrados:</h6>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sistema-parametros.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        @php
                            $descricaoPorCategoria = [
                                'red' => 'Emergência',
                                'orange' => 'Muito Urgente',
                                'yellow' => 'Urgente',
                                'green' => 'Pouco Urgente',
                                'blue' => 'Não Urgente',
                            ];
                        @endphp
                        @foreach($parametros as $parametro)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header d-flex align-items-center justify-content-between" 
                                         style="background: linear-gradient(135deg, {{ $parametro->categoria_risco == 'red' ? '#dc3545' : ($parametro->categoria_risco == 'orange' ? '#fd7e14' : ($parametro->categoria_risco == 'yellow' ? '#ffc107' : ($parametro->categoria_risco == 'green' ? '#198754' : '#0d6efd'))) }} 0%, {{ $parametro->categoria_risco == 'red' ? '#c82333' : ($parametro->categoria_risco == 'orange' ? '#e55a00' : ($parametro->categoria_risco == 'yellow' ? '#e0a800' : ($parametro->categoria_risco == 'green' ? '#157347' : '#0b5ed7'))) }} 100%); color: white;">
                                        <h6 class="mb-0">
                                            <i class="fas fa-{{ $parametro->categoria_risco == 'red' ? 'heartbeat' : ($parametro->categoria_risco == 'orange' ? 'exclamation-triangle' : ($parametro->categoria_risco == 'yellow' ? 'clock' : ($parametro->categoria_risco == 'green' ? 'check-circle' : 'info-circle'))) }}"></i>
                                            {{ $descricaoPorCategoria[$parametro->categoria_risco] ?? ucfirst($parametro->categoria_risco) }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="parametros[{{ $loop->index }}][id]" value="{{ $parametro->id }}">
                                        <!-- Campos mantidos como hidden para preservar dados; UI mostra apenas tempo de reavaliação -->
                                        <input type="hidden" 
                                               name="parametros[{{ $loop->index }}][nome_categoria]" 
                                               value="{{ old('parametros.'.$loop->index.'.nome_categoria', $parametro->nome_categoria) }}">
                                        <input type="hidden" 
                                               name="parametros[{{ $loop->index }}][descricao]" 
                                               value="{{ old('parametros.'.$loop->index.'.descricao', $parametro->descricao) }}">
                                        <input type="hidden" 
                                               name="parametros[{{ $loop->index }}][ativo]" 
                                               value="{{ $parametro->ativo ? 1 : 0 }}">

                                        <div class="mb-3">
                                            <label for="tempo_{{ $parametro->id }}" class="form-label fw-bold">
                                                Tempo de Reavaliação (minutos)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control" 
                                                       id="tempo_{{ $parametro->id }}" 
                                                       name="parametros[{{ $loop->index }}][tempo_reavaliacao_minutos]" 
                                                       value="{{ old('parametros.'.$loop->index.'.tempo_reavaliacao_minutos', $parametro->tempo_reavaliacao_minutos) }}"
                                                       min="1" 
                                                       max="1440" 
                                                       required>
                                                <span class="input-group-text">min</span>
                                            </div>
                                            <small class="form-text text-muted">
                                                Equivale a {{ $parametro->tempo_reavaliacao_minutos >= 60 ? floor($parametro->tempo_reavaliacao_minutos / 60) . 'h ' . ($parametro->tempo_reavaliacao_minutos % 60) . 'min' : $parametro->tempo_reavaliacao_minutos . ' minutos' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('sistema-parametros.reset') }}" 
                                   class="btn btn-warning"
                                   onclick="return confirm('Tem certeza que deseja resetar todos os parâmetros para os valores padrão? Esta ação não pode ser desfeita.')">
                                    <i class="fas fa-undo"></i>
                                    Resetar para Padrão
                                </a>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Salvar Alterações
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Atualizar preview do tempo em tempo real
    document.querySelectorAll('input[name*="[tempo_reavaliacao_minutos]"]').forEach(input => {
        input.addEventListener('input', function() {
            const minutes = parseInt(this.value) || 0;
            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;
            
            const preview = this.parentNode.nextElementSibling;
            if (preview) {
                if (hours > 0) {
                    preview.textContent = `Equivale a ${hours}h ${remainingMinutes}min`;
                } else {
                    preview.textContent = `Equivale a ${minutes} minutos`;
                }
            }
        });
    });

    // Validação de formulário (tempos)
    const formTempos = document.querySelector('form[action*="sistema-parametros"]');
    if (formTempos) {
        formTempos.addEventListener('submit', function(e) {
            let hasError = false;
            const timeInputs = document.querySelectorAll('input[name*="[tempo_reavaliacao_minutos]"]');
            timeInputs.forEach(input => {
                const value = parseInt(input.value);
                if (value < 1 || value > 1440) {
                    alert('Os tempos de reavaliação devem estar entre 1 e 1440 minutos (24 horas)!');
                    hasError = true;
                }
            });
            if (hasError) {
                e.preventDefault();
            }
        });
    }
</script>
@endsection






