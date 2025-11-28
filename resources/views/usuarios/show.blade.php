@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detalhes do Usuário</h3>
                    <div>
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Dados Pessoais -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Dados Pessoais</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nome:</strong></td>
                                    <td>{{ $usuario->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sobrenome:</strong></td>
                                    <td>{{ $usuario->sobrenome ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nome Completo:</strong></td>
                                    <td>{{ $usuario->nome_completo }}</td>
                                </tr>
                                <tr>
                                    <td><strong>CPF:</strong></td>
                                    <td>{{ $usuario->cpf }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Data de Nascimento:</strong></td>
                                    <td>{{ $usuario->data_nascimento ? $usuario->data_nascimento->format('d/m/Y') : 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sexo:</strong></td>
                                    <td>{{ $usuario->sexo ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Estado Civil:</strong></td>
                                    <td>{{ $usuario->estado_civil ?? 'Não informado' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Contato -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Contato</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $usuario->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telefone:</strong></td>
                                    <td>{{ $usuario->telefone ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Celular:</strong></td>
                                    <td>{{ $usuario->celular ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>CRM:</strong></td>
                                    <td>{{ $usuario->crm ?? 'Não informado' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <!-- Endereço -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Endereço</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>CEP:</strong></td>
                                    <td>{{ $usuario->cep ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Endereço:</strong></td>
                                    <td>{{ $usuario->endereco ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Número:</strong></td>
                                    <td>{{ $usuario->numero ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Complemento:</strong></td>
                                    <td>{{ $usuario->complemento ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bairro:</strong></td>
                                    <td>{{ $usuario->bairro ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cidade:</strong></td>
                                    <td>{{ $usuario->cidade ?? 'Não informado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td>{{ $usuario->estado ?? 'Não informado' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Sistema -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Dados do Sistema</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Tipo de Usuário:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ $usuario->tipo_usuario }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($usuario->ativo)
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-danger">Inativo</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Último Acesso:</strong></td>
                                    <td>{{ $usuario->ultimo_acesso ? $usuario->ultimo_acesso->format('d/m/Y H:i') : 'Nunca' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Criado em:</strong></td>
                                    <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Atualizado em:</strong></td>
                                    <td>{{ $usuario->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Perfis removidos: autorização baseada em tipo_usuario e permissões -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

