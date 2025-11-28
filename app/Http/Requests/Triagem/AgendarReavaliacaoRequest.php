<?php

declare(strict_types=1);

namespace App\Http\Requests\Triagem;

use Illuminate\Foundation\Http\FormRequest;

class AgendarReavaliacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_reavaliacao' => 'required|date|after:now'
        ];
    }
}


