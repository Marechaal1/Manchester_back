<?php

declare(strict_types=1);

namespace App\Application\UseCases\Triagem;

use App\Domain\Repositories\TriagemRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\Triagem;

class ObterHistoricoTriagensPacienteUseCase
{
    public function __construct(
        private TriagemRepositoryInterface $triagemRepository
    ) {
    }

    public function execute(string $pacienteId): Collection
    {
        return $this->triagemRepository->historicoPorPaciente($pacienteId);
    }
}


