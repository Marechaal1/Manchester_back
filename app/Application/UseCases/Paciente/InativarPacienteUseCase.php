<?php

declare(strict_types=1);

namespace App\Application\UseCases\Paciente;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repositories\PacienteRepositoryInterface;

class InativarPacienteUseCase
{
    public function __construct(
        private PacienteRepositoryInterface $pacienteRepository
    ) {
    }

    public function execute(string $pacienteId): void
    {
        $paciente = $this->pacienteRepository->findById($pacienteId);
        
        if (!$paciente) {
            throw new EntityNotFoundException('Paciente', $pacienteId);
        }

        $this->pacienteRepository->inativar($paciente);
    }
}


