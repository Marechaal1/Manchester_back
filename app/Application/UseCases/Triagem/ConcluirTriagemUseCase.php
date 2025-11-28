<?php

declare(strict_types=1);

namespace App\Application\UseCases\Triagem;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\InvalidOperationException;
use App\Domain\Repositories\TriagemRepositoryInterface;
use App\Models\Triagem;

class ConcluirTriagemUseCase
{
    public function __construct(
        private TriagemRepositoryInterface $triagemRepository
    ) {
    }

    public function execute(string $triagemId): Triagem
    {
        $triagem = $this->triagemRepository->findById($triagemId);
        
        if (!$triagem) {
            throw new EntityNotFoundException('Triagem', $triagemId);
        }

        if ($triagem->status === 'CONCLUIDA') {
            throw new InvalidOperationException('Triagem já foi concluída');
        }

        return $this->triagemRepository->update($triagem, [
            'status' => 'CONCLUIDA',
            'data_conclusao' => now()
        ]);
    }
}


