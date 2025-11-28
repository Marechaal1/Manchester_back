<?php

declare(strict_types=1);

namespace App\Application\UseCases\Paciente;

use App\Domain\Repositories\PacienteRepositoryInterface;
use App\Domain\Services\CpfService;
use App\Models\Paciente;

class CriarPacienteUseCase
{
    public function __construct(
        private PacienteRepositoryInterface $pacienteRepository,
        private CpfService $cpfService
    ) {
    }

    public function execute(array $dados): Paciente
    {
        if (isset($dados['cpf'])) {
            $dados['cpf'] = $this->cpfService->normalizarParaMascara((string) $dados['cpf']);
        }

        return $this->pacienteRepository->create($dados);
    }
}


