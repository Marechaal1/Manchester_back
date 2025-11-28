<?php

declare(strict_types=1);

namespace App\Application\UseCases\Paciente;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repositories\PacienteRepositoryInterface;
use App\Models\Paciente;

class ObterPacienteUseCase
{
    public function __construct(
        private PacienteRepositoryInterface $pacienteRepository
    ) {
    }

    public function execute(string $pacienteId): Paciente
    {
        $paciente = $this->pacienteRepository->findAtivoComTriagensUsuario($pacienteId);
        
        if (!$paciente) {
            throw new EntityNotFoundException('Paciente', $pacienteId);
        }

        return $paciente;
    }
}


