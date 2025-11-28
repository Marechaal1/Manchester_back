<?php

declare(strict_types=1);

namespace App\Application\UseCases\Triagem;

use App\Application\DTOs\TriagemFiltersDTO;
use App\Domain\Repositories\TriagemRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListarTriagensAtivasUseCase
{
    public function __construct(
        private TriagemRepositoryInterface $triagemRepository
    ) {
    }

    public function execute(TriagemFiltersDTO $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->triagemRepository->paginateAtivas($filters->toArray(), $perPage);
    }
}


