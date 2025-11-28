<?php

declare(strict_types=1);

namespace App\Application\UseCases\Triagem;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repositories\TriagemRepositoryInterface;
use App\Models\Triagem;

class ObterTriagemUseCase
{
    public function __construct(
        private TriagemRepositoryInterface $triagemRepository
    ) {
    }

    public function execute(string $triagemId, bool $comRelacoes = true): Triagem
    {
        $triagem = $comRelacoes
            ? $this->triagemRepository->findWithRelations($triagemId)
            : $this->triagemRepository->findById($triagemId);
        
        if (!$triagem) {
            throw new EntityNotFoundException('Triagem', $triagemId);
        }

        return $triagem;
    }
}


