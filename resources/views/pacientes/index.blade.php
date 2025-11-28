@extends('layouts.app')

@section('title', 'Gestão de Pacientes')
@section('page-title', 'Pacientes')
@section('page-subtitle', 'Gestão de Pacientes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Gestão de Pacientes</h3>
                    <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Novo Paciente
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        @php $dirId = (($sort ?? 'nome') === 'id' && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'; @endphp
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'dir' => $dirId]) }}" class="text-decoration-none text-dark">
                                            ID
                                            @if(($sort ?? '') === 'id')
                                                <i class="fas fa-sort-{{ ($dir ?? 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        @php $dirNome = (($sort ?? 'nome') === 'nome' && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'; @endphp
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nome', 'dir' => $dirNome]) }}" class="text-decoration-none text-dark">
                                            Nome
                                            @if(($sort ?? '') === 'nome')
                                                <i class="fas fa-sort-{{ ($dir ?? 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        @php $dirCpf = (($sort ?? '') === 'cpf' && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'; @endphp
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'cpf', 'dir' => $dirCpf]) }}" class="text-decoration-none text-dark">
                                            CPF
                                            @if(($sort ?? '') === 'cpf')
                                                <i class="fas fa-sort-{{ ($dir ?? 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        @php $dirIdade = (($sort ?? '') === 'idade' && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'; @endphp
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'idade', 'dir' => $dirIdade]) }}" class="text-decoration-none text-dark">
                                            Idade
                                            @if(($sort ?? '') === 'idade')
                                                <i class="fas fa-sort-{{ ($dir ?? 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        @php $dirTel = (($sort ?? '') === 'telefone' && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'; @endphp
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'telefone', 'dir' => $dirTel]) }}" class="text-decoration-none text-dark">
                                            Telefone
                                            @if(($sort ?? '') === 'telefone')
                                                <i class="fas fa-sort-{{ ($dir ?? 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        @php $dirUlt = (($sort ?? '') === 'ultima_triagem' && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'; @endphp
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'ultima_triagem', 'dir' => $dirUlt]) }}" class="text-decoration-none text-dark">
                                            Última Triagem
                                            @if(($sort ?? '') === 'ultima_triagem')
                                                <i class="fas fa-sort-{{ ($dir ?? 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pacientes as $paciente)
                                    @php $ultima = $paciente->triagens->first(); @endphp
                                    <tr>
                                        <td>{{ $paciente->id }}</td>
                                        <td>{{ $paciente->nome_completo }}</td>
                                        <td>{{ $paciente->cpf }}</td>
                                        <td>{{ $paciente->idade ?? '-' }}</td>
                                        <td>{{ $paciente->telefone ?? '-' }}</td>
                                        <td>{{ $ultima?->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group pacientes-actions">
                                                <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-sm btn-info btn-icon" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pacientes.historico', $paciente) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Histórico">
                                                    <i class="fas fa-clock-rotate-left"></i>
                                                </a>
                                                <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sm btn-primary" title="Editar cadastro">
                                                    <i class="fas fa-user-edit"></i> Editar cadastro
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum paciente triado encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $pacientes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
