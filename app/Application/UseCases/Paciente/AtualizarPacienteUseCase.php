<?php

declare(strict_types=1);

namespace App\Application\UseCases\Paciente;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repositories\PacienteRepositoryInterface;
use App\Domain\Services\CpfService;
use App\Models\Paciente;

class AtualizarPacienteUseCase
{
    public function __construct(
        private PacienteRepositoryInterface $pacienteRepository,
        private CpfService $cpfService
    ) {
    }

    public function execute(string $pacienteId, array $dados): Paciente
    {
        $paciente = $this->pacienteRepository->findById($pacienteId);
        
        if (!$paciente) {
            throw new EntityNotFoundException('Paciente', $pacienteId);
        }

        if (isset($dados['cpf'])) {
            $dados['cpf'] = $this->cpfService->normalizarParaMascara((string) $dados['cpf']);
        }

        return $this->pacienteRepository->update($paciente, $dados);
    }
}


