@extends('layouts.app')

@section('title', 'Novo Paciente')
@section('page-title', 'Novo Paciente')
@section('page-subtitle', 'Cadastrar novo paciente')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Cadastrar novo paciente</h3>
                    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('pacientes.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nome completo *</label>
                                <input type="text" name="nome_completo" class="form-control @error('nome_completo') is-invalid @enderror" value="{{ old('nome_completo') }}" required>
                                @error('nome_completo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="cpf">CPF *</label>
                                <input id="cpf" type="text" name="cpf" class="form-control @error('cpf') is-invalid @enderror" value="{{ old('cpf') }}" required inputmode="numeric" autocomplete="off">
                                @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Data de nascimento *</label>
                                <input type="date" name="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror" value="{{ old('data_nascimento') }}" required>
                                @error('data_nascimento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sexo *</label>
                                <select name="sexo" class="form-select @error('sexo') is-invalid @enderror" required>
                                    <option value="">Selecione...</option>
                                    <option value="MASCULINO" {{ old('sexo') == 'MASCULINO' ? 'selected' : '' }}>Masculino</option>
                                    <option value="FEMININO" {{ old('sexo') == 'FEMININO' ? 'selected' : '' }}>Feminino</option>
                                    <option value="OUTRO" {{ old('sexo') == 'OUTRO' ? 'selected' : '' }}>Outro</option>
                                </select>
                                @error('sexo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email</label>
                                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div id="email-feedback" class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefone</label>
                                <input id="telefone" type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone') }}" inputmode="numeric" autocomplete="tel">
                                @error('telefone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CEP</label>
                                <input type="text" name="cep" class="form-control @error('cep') is-invalid @enderror" value="{{ old('cep') }}">
                                @error('cep')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Endereço</label>
                                <input type="text" name="endereco" class="form-control @error('endereco') is-invalid @enderror" value="{{ old('endereco') }}">
                                @error('endereco')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Cidade</label>
                                <input type="text" name="cidade" class="form-control @error('cidade') is-invalid @enderror" value="{{ old('cidade') }}">
                                @error('cidade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                    @php
                                        $ufs = [
                                            'AC' => 'Acre','AL' => 'Alagoas','AP' => 'Amapá','AM' => 'Amazonas','BA' => 'Bahia',
                                            'CE' => 'Ceará','DF' => 'Distrito Federal','ES' => 'Espírito Santo','GO' => 'Goiás','MA' => 'Maranhão',
                                            'MT' => 'Mato Grosso','MS' => 'Mato Grosso do Sul','MG' => 'Minas Gerais','PA' => 'Pará','PB' => 'Paraíba',
                                            'PR' => 'Paraná','PE' => 'Pernambuco','PI' => 'Piauí','RJ' => 'Rio de Janeiro','RN' => 'Rio Grande do Norte',
                                            'RS' => 'Rio Grande do Sul','RO' => 'Rondônia','RR' => 'Roraima','SC' => 'Santa Catarina','SP' => 'São Paulo',
                                            'SE' => 'Sergipe','TO' => 'Tocantins',
                                        ];
                                    @endphp
                                    <option value="">Selecione...</option>
                                    @foreach($ufs as $sigla => $nome)
                                        <option value="{{ $sigla }}" {{ old('estado') === $sigla ? 'selected' : '' }}>{{ $sigla }} - {{ $nome }}</option>
                                    @endforeach
                                </select>
                                @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nome do responsável</label>
                                <input type="text" name="nome_responsavel" class="form-control @error('nome_responsavel') is-invalid @enderror" value="{{ old('nome_responsavel') }}">
                                @error('nome_responsavel')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefone do responsável</label>
                                <input id="telefone_responsavel" type="text" name="telefone_responsavel" class="form-control @error('telefone_responsavel') is-invalid @enderror" value="{{ old('telefone_responsavel') }}" inputmode="numeric" autocomplete="tel">
                                @error('telefone_responsavel')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Observações</label>
                                <textarea name="observacoes" rows="4" class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes') }}</textarea>
                                @error('observacoes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar
                                </button>
                                <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
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

document.addEventListener('DOMContentLoaded', function () {
    bindMascaraTelefone('telefone');
    bindMascaraTelefone('telefone_responsavel');
    bindValidacaoEmail('email', 'email-feedback');
    bindMascaraCPF('cpf');
});

// Máscara de CPF: 000.000.000-00 (limite: 11 dígitos)
function aplicarMascaraCPF(valor) {
    const digits = (valor || '').replace(/\D/g, '').slice(0, 11);
    const d = digits;
    if (d.length <= 3) return d;
    if (d.length <= 6) return `${d.slice(0,3)}.${d.slice(3)}`;
    if (d.length <= 9) return `${d.slice(0,3)}.${d.slice(3,6)}.${d.slice(6)}`;
    return `${d.slice(0,3)}.${d.slice(3,6)}.${d.slice(6,9)}-${d.slice(9,11)}`;
}

function bindMascaraCPF(id) {
    const input = document.getElementById(id);
    if (!input) return;
    const format = () => { input.value = aplicarMascaraCPF(input.value); };
    input.addEventListener('input', format);
    input.addEventListener('blur', format);
    format();
}
</script>
@endsection





