<?php

declare(strict_types=1);

namespace App\Application\UseCases\Paciente;

use App\Application\DTOs\PacienteFiltersDTO;
use App\Domain\Repositories\PacienteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListarPacientesUseCase
{
    public function __construct(
        private PacienteRepositoryInterface $pacienteRepository
    ) {
    }

    public function execute(PacienteFiltersDTO $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->pacienteRepository->paginateAtivos($filters->toArray(), $perPage);
    }
}


