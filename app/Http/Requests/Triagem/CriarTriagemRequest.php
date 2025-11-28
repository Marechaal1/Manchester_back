<?php

declare(strict_types=1);

namespace App\Http\Requests\Triagem;

use Illuminate\Foundation\Http\FormRequest;

class CriarTriagemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paciente_id' => 'required|exists:pacientes,id',
            'classificacao_risco' => 'required|in:VERMELHO,LARANJA,AMARELO,VERDE,AZUL',
            'dados_clinicos' => 'nullable|array',
            'diagnosticos_enfermagem' => 'nullable|array',
            'intervencoes_enfermagem' => 'nullable|array',
            'avaliacao_seguranca' => 'nullable|array',
            'observacoes' => 'nullable|string'
        ];
    }
}


