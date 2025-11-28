<?php

declare(strict_types=1);

namespace App\Application\DTOs;

readonly class AtualizarTriagemDTO
{
    public function __construct(
        public ?string $classificacaoRisco = null,
        public ?array $dadosClinicos = null,
        public ?array $diagnosticosEnfermagem = null,
        public ?array $intervencoesEnfermagem = null,
        public ?array $avaliacaoSeguranca = null,
        public ?string $observacoes = null,
        public ?string $status = null,
    ) {
    }
}


