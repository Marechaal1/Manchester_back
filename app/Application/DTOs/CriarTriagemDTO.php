<?php

declare(strict_types=1);

namespace App\Application\DTOs;

readonly class CriarTriagemDTO
{
    public function __construct(
        public string $pacienteId,
        public string $classificacaoRisco,
        public string $usuarioId,
        public ?array $dadosClinicos = null,
        public ?array $diagnosticosEnfermagem = null,
        public ?array $intervencoesEnfermagem = null,
        public ?array $avaliacaoSeguranca = null,
        public ?string $observacoes = null,
    ) {
    }
}


