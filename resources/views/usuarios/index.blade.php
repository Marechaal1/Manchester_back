@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Gerenciar Usuários</h3>
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Novo Usuário
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>CPF</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $usuario->id }}</td>
                                        <td>{{ trim(($usuario->name ?? '') . ' ' . ($usuario->sobrenome ?? '')) }}</td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>{{ $usuario->cpf }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $usuario->tipo_usuario }}</span>
                                        </td>
                                        <td>
                                            @if($usuario->ativo)
                                                <span class="badge bg-success">Ativo</span>
                                            @else
                                                <span class="badge bg-danger">Inativo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group actions-group" role="group">
                                                <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-sm btn-info btn-icon" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-warning btn-icon" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($usuario->ativo)
                                                    <form action="{{ route('usuarios.inativar', $usuario) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-secondary btn-icon" title="Inativar" onclick="return confirm('Deseja inativar este usuário?')">
                                                            <i class="fas fa-user-times"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('usuarios.ativar', $usuario) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success btn-icon" title="Ativar" onclick="return confirm('Deseja ativar este usuário?')">
                                                            <i class="fas fa-user-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum usuário encontrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $usuarios->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

