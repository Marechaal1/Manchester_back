<?php

declare(strict_types=1);

namespace App\Application\UseCases\Triagem;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repositories\TriagemRepositoryInterface;
use App\Models\Triagem;
use Carbon\Carbon;

class AgendarReavaliacaoUseCase
{
    public function __construct(
        private TriagemRepositoryInterface $triagemRepository
    ) {
    }

    public function execute(string $triagemId, Carbon $dataReavaliacao): Triagem
    {
        $triagem = $this->triagemRepository->findById($triagemId);
        
        if (!$triagem) {
            throw new EntityNotFoundException('Triagem', $triagemId);
        }

        return $this->triagemRepository->update($triagem, [
            'requer_reavaliacao' => true,
            'data_reavaliacao' => $dataReavaliacao
        ]);
    }
}


