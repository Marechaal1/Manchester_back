@extends('layouts.app')

@section('title', 'Parâmetros do Sistema — Templates de Evolução')
@section('page-title', 'Parâmetros do Sistema')
@section('page-subtitle', 'Templates de Evolução de Enfermagem')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-0"><i class="fas fa-file-alt"></i> Templates de Evolução</h5>
                    <p class="card-text mt-2 mb-0 text-muted">Cadastre e personalize os modelos de texto usados na evolução de enfermagem.</p>
                </div>
                <a href="{{ route('sistema-parametros.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
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

                <form action="{{ route('sistema-parametros.templates-evolucao.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Título</label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ex.: Avaliação Geral" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Conteúdo</label>
                            <textarea name="conteudo" class="form-control" rows="2" placeholder="Texto do template..." style="min-height:38px; max-height:120px; resize:vertical;" required></textarea>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="ativo" value="1" id="novo_ativo" checked>
                                <label class="form-check-label" for="novo_ativo">Ativo</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success btn-sm d-flex align-items-center gap-1 px-2 py-1"><i class="fas fa-plus"></i><span>Adicionar Template</span></button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped align-middle table-sm">
                        <thead>
                            <tr>
                                <th style="width: 90px;">Ativo</th>
                                <th style="width: 240px;">Título</th>
                                <th>Conteúdo</th>
                                <th style="width: 150px;" class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($itens as $t)
                                <tr>
                                    <form action="{{ route('sistema-parametros.templates-evolucao.update', $t->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="ativo" value="1" {{ $t->ativo ? 'checked' : '' }} />
                                            </div>
                                        </td>
                                        <td><input type="text" name="titulo" value="{{ $t->titulo }}" class="form-control form-control-sm" required /></td>
                                        <td><textarea name="conteudo" class="form-control form-control-sm" rows="2" style="min-height:38px; max-height:120px; resize:vertical;">{{ $t->conteudo }}</textarea></td>
                                        <td class="text-end">
                                            <div class="d-flex flex-column align-items-end gap-1">
                                                <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1 px-2 py-1"><i class="fas fa-save"></i><span>Salvar</span></button>
                                    </form>
                                                <form action="{{ route('sistema-parametros.templates-evolucao.destroy', $t->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 px-2 py-1" onclick="return confirm('Remover template?')"><i class="fas fa-trash"></i><span>Remover</span></button>
                                                </form>
                                            </div>
                                        </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Nenhum template cadastrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $itens->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection












