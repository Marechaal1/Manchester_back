<?php

declare(strict_types=1);

namespace App\Application\DTOs;

readonly class PacienteFiltersDTO
{
    public function __construct(
        public ?string $busca = null,
        public ?string $sexo = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'busca' => $this->busca,
            'sexo' => $this->sexo,
        ], fn($value) => $value !== null);
    }
}


