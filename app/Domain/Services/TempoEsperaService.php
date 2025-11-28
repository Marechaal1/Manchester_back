<?php

namespace App\Domain\Services;

class TempoEsperaService
{
    public function calcular(string $classificacao): int
    {
        return match ($classificacao) {
            'VERMELHO' => 0,
            'LARANJA' => 10,
            'AMARELO' => 60,
            'VERDE' => 120,
            'AZUL' => 240,
            default => 120,
        };
    }
}




