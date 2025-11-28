@extends('layouts.app')

@section('title', 'Parâmetros do Sistema — Diagnósticos (CIPE)')
@section('page-title', 'Parâmetros do Sistema')
@section('page-subtitle', 'Gerenciador de Diagnósticos (CIPE)')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-0">
                        <i class="fas fa-notes-medical"></i>
                        Gerenciador de Diagnósticos (CIPE)
                    </h5>
                    <p class="card-text mt-2 mb-0 text-muted">
                        Cadastre, edite ou remova diagnósticos CIPE utilizados pelo app.
                    </p>
                </div>
                <a href="{{ route('sistema-parametros.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Formulário de criação --}}
                <form action="{{ route('sistema-parametros.diagnosticos.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Código</label>
                            <input type="text" name="codigo" class="form-control" placeholder="00000" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Título</label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ex.: Dor aguda" required />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Domínio</label>
                            <input type="text" name="dominio" class="form-control" placeholder="Ex.: Conforto" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Categoria</label>
                            <input type="text" name="categoria" class="form-control" placeholder="Ex.: Dor" />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Definição</label>
                            <textarea name="definicao" class="form-control" rows="2" placeholder="Definição CIPE..." style="min-height:38px; max-height:120px; resize:vertical;"></textarea>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="ativo" value="1" id="novo_ativo" checked>
                                <label class="form-check-label" for="novo_ativo">Ativo</label>
                            </div>
                        </div>
                        <div class="col-md-10 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success btn-sm d-flex align-items-center gap-1 px-2 py-1">
                                <i class="fas fa-plus"></i>
                                <span>Adicionar Diagnóstico</span>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Tabela de diagnósticos --}}
                <div class="table-responsive">
                    <table class="table table-striped align-middle table-sm">
                        <thead>
                            <tr>
                                <th style="width: 90px;">Ativo</th>
                                <th style="width: 120px;">Código</th>
                                <th>Título</th>
                                <th>Domínio</th>
                                <th>Categoria</th>
                                <th style="min-width:320px; width:35%;">Definição</th>
                                <th style="width: 150px;" class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($diagnosticos as $d)
                                <tr>
                                    <form action="{{ route('sistema-parametros.diagnosticos.update', $d->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="ativo" value="1" {{ $d->ativo ? 'checked' : '' }} />
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="codigo" value="{{ $d->codigo }}" class="form-control form-control-sm" />
                                        </td>
                                        <td>
                                            <input type="text" name="titulo" value="{{ $d->titulo }}" class="form-control form-control-sm" required />
                                        </td>
                                        <td>
                                            <input type="text" name="dominio" value="{{ $d->dominio }}" class="form-control form-control-sm" />
                                        </td>
                                        <td>
                                            <input type="text" name="categoria" value="{{ $d->categoria }}" class="form-control form-control-sm" />
                                        </td>
                                        <td style="min-width:320px; width:35%;">
                                            <textarea name="definicao" class="form-control form-control-sm" rows="2" style="min-height:38px; max-height:120px; resize:vertical;">{{ $d->definicao }}</textarea>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex flex-column align-items-end gap-1" style="width: 100px;">
                                                <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center gap-1 w-100">
                                                    <i class="fas fa-save"></i>
                                                    <span>Salvar</span>
                                                </button>
                                            </form>
                                                <form action="{{ route('sistema-parametros.diagnosticos.destroy', $d->id) }}" method="POST" class="d-inline w-100">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center gap-1 w-100" onclick="return confirm('Remover diagnóstico?')">
                                                        <i class="fas fa-trash"></i>
                                                        <span>Remover</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Nenhum diagnóstico cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Rodapé com paginação e controles --}}
            <div class="card-footer">
                <div class="row align-items-center g-3">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('sistema-parametros.diagnosticos') }}" class="d-flex align-items-center gap-2">
                            <label class="mb-0 text-muted">Itens por página</label>
                            <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                                @foreach(($allowedPerPage ?? [10,25,50,100]) as $opt)
                                    <option value="{{ $opt }}" {{ (int)request('per_page', $perPage ?? 25) === (int)$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                            @foreach(request()->except(['per_page','page']) as $k => $v)
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                            @endforeach
                        </form>
                    </div>
                    <div class="col-md-4 text-center text-muted small">
                        @php
                            $from = ($diagnosticos->currentPage() - 1) * $diagnosticos->perPage() + 1;
                            $to = min($diagnosticos->total(), $diagnosticos->currentPage() * $diagnosticos->perPage());
                        @endphp
                        Mostrando {{ $from }}–{{ $to }} de {{ $diagnosticos->total() }} registros
                    </div>
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('sistema-parametros.diagnosticos') }}" class="d-flex align-items-center gap-2 justify-content-md-end">
                            <label class="mb-0 text-muted">Ir para página</label>
                            <input type="number" min="1" max="{{ $diagnosticos->lastPage() }}" name="page" value="{{ $diagnosticos->currentPage() }}" class="form-control form-control-sm" style="width: 90px;" />
                            <input type="hidden" name="per_page" value="{{ (int)request('per_page', $perPage ?? 25) }}" />
                            @foreach(request()->except(['per_page','page']) as $k => $v)
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                            @endforeach
                            <button class="btn btn-sm btn-primary">Ir</button>
                        </form>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $diagnosticos->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
