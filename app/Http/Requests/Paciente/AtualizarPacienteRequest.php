<?php

declare(strict_types=1);

namespace App\Http\Requests\Paciente;

use Illuminate\Foundation\Http\FormRequest;

class AtualizarPacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pacienteId = $this->route('paciente') ?? $this->route('id');
        
        return [
            'nome_completo' => 'required|string|max:255',
            'cpf' => 'required|string|size:14|unique:pacientes,cpf,' . $pacienteId,
            'data_nascimento' => 'required|date|before:today',
            'sexo' => 'required|in:M,F,O',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'endereco' => 'nullable|string',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|size:2',
            'cep' => 'nullable|string|max:10',
            'nome_responsavel' => 'nullable|string|max:255',
            'telefone_responsavel' => 'nullable|string|max:20',
            'observacoes' => 'nullable|string'
        ];
    }
}


