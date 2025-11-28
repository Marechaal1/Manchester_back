@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Usuário</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('usuarios.update', $usuario) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        
                        <!-- Dados Pessoais -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary">Dados Pessoais</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nome *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $usuario->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sobrenome" class="form-label">Sobrenome *</label>
                                <input type="text" class="form-control @error('sobrenome') is-invalid @enderror" 
                                       id="sobrenome" name="sobrenome" value="{{ old('sobrenome', $usuario->sobrenome) }}" required>
                                @error('sobrenome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome_completo" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control @error('nome_completo') is-invalid @enderror" 
                                       id="nome_completo" name="nome_completo" value="{{ old('nome_completo', $usuario->nome_completo) }}" required>
                                @error('nome_completo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cpf" class="form-label">CPF *</label>
                                <input type="text" class="form-control @error('cpf') is-invalid @enderror" 
                                       id="cpf" name="cpf" value="{{ old('cpf', $usuario->cpf) }}" required>
                                @error('cpf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="data_nascimento" class="form-label">Data de Nascimento *</label>
                                <input type="date" class="form-control @error('data_nascimento') is-invalid @enderror" 
                                       id="data_nascimento" name="data_nascimento" 
                                       value="{{ old('data_nascimento', $usuario->data_nascimento?->format('Y-m-d')) }}" required>
                                @error('data_nascimento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="sexo" class="form-label">Sexo *</label>
                                <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" name="sexo" required>
                                    <option value="">Selecione...</option>
                                    <option value="MASCULINO" {{ old('sexo', $usuario->sexo) == 'MASCULINO' ? 'selected' : '' }}>Masculino</option>
                                    <option value="FEMININO" {{ old('sexo', $usuario->sexo) == 'FEMININO' ? 'selected' : '' }}>Feminino</option>
                                    <option value="OUTRO" {{ old('sexo', $usuario->sexo) == 'OUTRO' ? 'selected' : '' }}>Outro</option>
                                </select>
                                @error('sexo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estado_civil" class="form-label">Estado Civil *</label>
                                <select class="form-select @error('estado_civil') is-invalid @enderror" id="estado_civil" name="estado_civil" required>
                                    <option value="">Selecione...</option>
                                    <option value="SOLTEIRO" {{ old('estado_civil', $usuario->estado_civil) == 'SOLTEIRO' ? 'selected' : '' }}>Solteiro</option>
                                    <option value="CASADO" {{ old('estado_civil', $usuario->estado_civil) == 'CASADO' ? 'selected' : '' }}>Casado</option>
                                    <option value="DIVORCIADO" {{ old('estado_civil', $usuario->estado_civil) == 'DIVORCIADO' ? 'selected' : '' }}>Divorciado</option>
                                    <option value="VIUVO" {{ old('estado_civil', $usuario->estado_civil) == 'VIUVO' ? 'selected' : '' }}>Viúvo</option>
                                    <option value="UNIAO_ESTAVEL" {{ old('estado_civil', $usuario->estado_civil) == 'UNIAO_ESTAVEL' ? 'selected' : '' }}>União Estável</option>
                                </select>
                                @error('estado_civil')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contato -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary">Contato</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $usuario->email) }}" autocapitalize="none" spellcheck="false" autocomplete="off" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="email-feedback" class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                       id="telefone" name="telefone" value="{{ old('telefone', $usuario->telefone) }}" inputmode="numeric" autocomplete="tel">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="celular" class="form-label">Celular</label>
                                <input type="text" class="form-control @error('celular') is-invalid @enderror" 
                                       id="celular" name="celular" value="{{ old('celular', $usuario->celular) }}" inputmode="numeric" autocomplete="tel">
                                @error('celular')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3" id="campo_crm">
                                <label for="crm" class="form-label">CRM</label>
                                <input type="text" class="form-control @error('crm') is-invalid @enderror" 
                                       id="crm" name="crm" value="{{ old('crm', $usuario->crm) }}" placeholder="Informe o CRM">
                                @error('crm')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3" id="campo_coren">
                                <label for="coren" class="form-label">COREN</label>
                                <input type="text" class="form-control @error('coren') is-invalid @enderror" 
                                       id="coren" name="coren" value="{{ old('coren', $usuario->coren) }}" placeholder="Informe o COREN">
                                @error('coren')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary">Endereço</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control @error('cep') is-invalid @enderror" 
                                       id="cep" name="cep" value="{{ old('cep', $usuario->cep) }}">
                                @error('cep')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control @error('endereco') is-invalid @enderror" 
                                       id="endereco" name="endereco" value="{{ old('endereco', $usuario->endereco) }}">
                                @error('endereco')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control @error('numero') is-invalid @enderror" 
                                       id="numero" name="numero" value="{{ old('numero', $usuario->numero) }}">
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control @error('complemento') is-invalid @enderror" 
                                       id="complemento" name="complemento" value="{{ old('complemento', $usuario->complemento) }}">
                                @error('complemento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control @error('bairro') is-invalid @enderror" 
                                       id="bairro" name="bairro" value="{{ old('bairro', $usuario->bairro) }}">
                                @error('bairro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control @error('cidade') is-invalid @enderror" 
                                       id="cidade" name="cidade" value="{{ old('cidade', $usuario->cidade) }}">
                                @error('cidade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" class="form-control @error('estado') is-invalid @enderror" 
                                       id="estado" name="estado" value="{{ old('estado', $usuario->estado) }}" maxlength="2">
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sistema -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary">Dados do Sistema</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="tipo_usuario" class="form-label">Tipo de Usuário *</label>
                                <select class="form-select @error('tipo_usuario') is-invalid @enderror" id="tipo_usuario" name="tipo_usuario" required>
                                    <option value="">Selecione...</option>
                                    <option value="ADMINISTRADOR" {{ old('tipo_usuario', $usuario->tipo_usuario) == 'ADMINISTRADOR' ? 'selected' : '' }}>Administrador</option>
                                    <option value="ENFERMEIRO" {{ old('tipo_usuario', $usuario->tipo_usuario) == 'ENFERMEIRO' ? 'selected' : '' }}>Enfermeiro</option>
                                    <option value="MEDICO" {{ old('tipo_usuario', $usuario->tipo_usuario) == 'MEDICO' ? 'selected' : '' }}>Médico</option>
                                </select>
                                @error('tipo_usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Permissões específicas para ENFERMEIRO -->
                        <div class="row" id="permissoes_enfermeiro" style="display: none;">
                            <div class="col-12">
                                <div class="alert alert-info d-flex align-items-start" role="alert">
                                    <i class="fas fa-user-nurse me-2 mt-1"></i>
                                    <div>
                                        <strong>Permissões do Enfermeiro</strong>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="permite_gerenciar_pacientes" name="permite_gerenciar_pacientes" value="1" 
                                                {{ old('permite_gerenciar_pacientes', $usuario->permite_gerenciar_pacientes ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permite_gerenciar_pacientes">
                                                Permitir gerenciar pacientes (cadastrar, atualizar e listar pacientes)
                                            </label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="permite_liberar_observacao" name="permite_liberar_observacao" value="1" 
                                                {{ old('permite_liberar_observacao', $usuario->permite_liberar_observacao ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permite_liberar_observacao">
                                                Permitir liberar pacientes em observação
                                            </label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="permite_extrair_relatorios" name="permite_extrair_relatorios" value="1" 
                                                {{ old('permite_extrair_relatorios', $usuario->permite_extrair_relatorios ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permite_extrair_relatorios">
                                                Permitir extrair relatórios
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissões específicas para MÉDICO -->
                        <div class="row" id="permissoes_medico" style="display: none;">
                            <div class="col-12">
                                <div class="alert alert-info d-flex align-items-start" role="alert">
                                    <i class="fas fa-user-md me-2 mt-1"></i>
                                    <div>
                                        <strong>Permissões do Médico</strong>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="permite_extrair_relatorios_medico" name="permite_extrair_relatorios" value="1" 
                                                {{ old('permite_extrair_relatorios', $usuario->permite_extrair_relatorios ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permite_extrair_relatorios_medico">
                                                Permitir extrair relatórios
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Atualizar Usuário
                                </button>
                                <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> Visualizar
                                </a>
                                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
// Máscara de telefone: (XX) X XXXX-XXXX
function aplicarMascaraTelefone(valor) {
    const digits = (valor || '').replace(/\D/g, '').slice(0, 11);
    if (digits.length === 0) return '';
    if (digits.length < 2) return '(' + digits;
    if (digits.length === 2) return `(${digits}) `;
    if (digits.length <= 6) return `(${digits.slice(0,2)}) ${digits.slice(2)}`;
    if (digits.length <= 10) return `(${digits.slice(0,2)}) ${digits.slice(2,6)}-${digits.slice(6)}`;
    return `(${digits.slice(0,2)}) ${digits.slice(2,3)} ${digits.slice(3,7)}-${digits.slice(7,11)}`;
}
function bindMascaraTelefone(id) {
    const input = document.getElementById(id);
    if (!input) return;
    const format = () => { input.value = aplicarMascaraTelefone(input.value); };
    input.addEventListener('input', format);
    input.addEventListener('blur', format);
    input.addEventListener('keydown', (e) => {
        if (e.key !== 'Backspace') return;
        const start = input.selectionStart;
        const end = input.selectionEnd;
        if (start !== end) return;
        const raw = input.value || '';
        const digits = raw.replace(/\D/g, '');
        if (start === raw.length && digits.length <= 2) {
            e.preventDefault();
            const newDigits = digits.slice(0, -1);
            input.value = aplicarMascaraTelefone(newDigits);
        }
    });
    format();
}

// Máscara de CPF: 000.000.000-00
function aplicarMascaraCPF(valor) {
    const digits = (valor || '').replace(/\D/g, '').slice(0, 11);
    if (digits.length === 0) return '';
    if (digits.length <= 3) return digits;
    if (digits.length <= 6) return `${digits.slice(0,3)}.${digits.slice(3)}`;
    if (digits.length <= 9) return `${digits.slice(0,3)}.${digits.slice(3,6)}.${digits.slice(6)}`;
    return `${digits.slice(0,3)}.${digits.slice(3,6)}.${digits.slice(6,9)}-${digits.slice(9,11)}`;
}
function bindMascaraCPF(id) {
    const input = document.getElementById(id);
    if (!input) return;
    const format = () => { input.value = aplicarMascaraCPF(input.value); };
    input.addEventListener('input', format);
    input.addEventListener('blur', format);
    format();
}

// Validação de e-mail onBlur com feedback
function bindValidacaoEmail(id, feedbackId) {
    const input = document.getElementById(id);
    const feedback = document.getElementById(feedbackId);
    if (!input || !feedback) return;
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    input.addEventListener('blur', () => {
        const value = (input.value || '').trim();
        if (value === '') {
            input.classList.remove('is-invalid');
            feedback.textContent = '';
            return;
        }
        const valido = regex.test(value);
        if (!valido) {
            input.classList.add('is-invalid');
            feedback.textContent = 'Informe um e-mail válido no formato nome@dominio.com';
        } else {
            input.classList.remove('is-invalid');
            feedback.textContent = '';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const selectTipo = document.getElementById('tipo_usuario');
    const campoCrm = document.getElementById('campo_crm');
    const campoCoren = document.getElementById('campo_coren');
    const blocoPermEnf = document.getElementById('permissoes_enfermeiro');
    const blocoPermMed = document.getElementById('permissoes_medico');
    const toggleProfInputs = () => {
        const v = (selectTipo.value || '').toUpperCase();
        campoCrm.style.display = (v === 'MEDICO') ? '' : 'none';
        campoCoren.style.display = (v === 'ENFERMEIRO') ? '' : 'none';
        if (blocoPermEnf) {
            blocoPermEnf.style.display = (v === 'ENFERMEIRO') ? '' : 'none';
        }
        if (blocoPermMed) {
            blocoPermMed.style.display = (v === 'MEDICO') ? '' : 'none';
        }
    };
    selectTipo.addEventListener('change', toggleProfInputs);
    toggleProfInputs();

    // Binds de máscara e validação
    bindMascaraTelefone('telefone');
    bindMascaraTelefone('celular');
    bindMascaraCPF('cpf');
    bindValidacaoEmail('email', 'email-feedback');
});
</script>
@endsection

