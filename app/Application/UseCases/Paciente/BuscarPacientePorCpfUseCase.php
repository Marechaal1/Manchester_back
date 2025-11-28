<?php

declare(strict_types=1);

namespace App\Application\UseCases\Paciente;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repositories\PacienteRepositoryInterface;
use App\Domain\Services\CpfService;
use App\Models\Paciente;

class BuscarPacientePorCpfUseCase
{
    public function __construct(
        private PacienteRepositoryInterface $pacienteRepository,
        private CpfService $cpfService
    ) {
    }

    public function execute(string $cpf): Paciente
    {
        $cpfDigits = $this->cpfService->somenteDigitos($cpf);
        $cpfMascarado = $this->cpfService->normalizarParaMascara($cpf);

        $paciente = $this->pacienteRepository->findAtivoByCpfOrDigits($cpfMascarado, $cpfDigits);
        
        if (!$paciente) {
            throw new EntityNotFoundException('Paciente');
        }

        return $paciente;
    }
}


