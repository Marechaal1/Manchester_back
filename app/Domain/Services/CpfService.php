<?php

namespace App\Domain\Services;

class CpfService
{
    public function somenteDigitos(string $cpf): string
    {
        return preg_replace('/\D/', '', $cpf);
    }

    public function normalizarParaMascara(string $cpf): string
    {
        $digits = $this->somenteDigitos($cpf);
        if (strlen($digits) !== 11) {
            return $cpf;
        }
        return substr($digits, 0, 3) . '.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-' . substr($digits, 9, 2);
    }
}




