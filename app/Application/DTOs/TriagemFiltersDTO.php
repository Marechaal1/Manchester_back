<?php

declare(strict_types=1);

namespace App\Application\DTOs;

readonly class TriagemFiltersDTO
{
    public function __construct(
        public ?string $status = null,
        public ?string $classificacaoRisco = null,
        public ?string $dataInicio = null,
        public ?string $dataFim = null,
        public ?string $usuarioId = null,
        public bool $excluirConcluidas = true,
        public bool $excluirComAtendimentoFinalizado = true,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'status' => $this->status,
            'classificacao_risco' => $this->classificacaoRisco,
            'data_inicio' => $this->dataInicio,
            'data_fim' => $this->dataFim,
            'usuario_id' => $this->usuarioId,
        ], fn($value) => $value !== null);
    }
}


