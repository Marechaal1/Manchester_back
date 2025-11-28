<?php

declare(strict_types=1);

namespace App\Application\UseCases\Triagem;

use App\Application\DTOs\AtualizarTriagemDTO;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repositories\TriagemRepositoryInterface;
use App\Domain\Services\TempoEsperaService;
use App\Models\Triagem;

class AtualizarTriagemUseCase
{
    public function __construct(
        private TriagemRepositoryInterface $triagemRepository,
        private TempoEsperaService $tempoEsperaService
    ) {
    }

    public function execute(string $triagemId, AtualizarTriagemDTO $dto): Triagem
    {
        $triagem = $this->triagemRepository->findById($triagemId);
        
        if (!$triagem) {
            throw new EntityNotFoundException('Triagem', $triagemId);
        }

        $dadosAtualizacao = [];

        if ($dto->classificacaoRisco !== null) {
            $dadosAtualizacao['classificacao_risco'] = $dto->classificacaoRisco;
            $dadosAtualizacao['tempo_espera_minutos'] = $this->tempoEsperaService->calcular($dto->classificacaoRisco);
        }

        if ($dto->dadosClinicos !== null) {
            $dadosAtualizacao['dados_clinicos'] = $dto->dadosClinicos;
        }

        if ($dto->diagnosticosEnfermagem !== null) {
            $dadosAtualizacao['diagnosticos_enfermagem'] = $dto->diagnosticosEnfermagem;
        }

        if ($dto->intervencoesEnfermagem !== null) {
            $dadosAtualizacao['intervencoes_enfermagem'] = $dto->intervencoesEnfermagem;
        }

        if ($dto->avaliacaoSeguranca !== null) {
            $dadosAtualizacao['avaliacao_seguranca'] = $dto->avaliacaoSeguranca;
        }

        if ($dto->observacoes !== null) {
            $dadosAtualizacao['observacoes'] = $dto->observacoes;
        }

        if ($dto->status !== null) {
            $dadosAtualizacao['status'] = $dto->status;
            
            if ($dto->status === 'CONCLUIDA') {
                $dadosAtualizacao['data_conclusao'] = now();
            }
        }

        return $this->triagemRepository->update($triagem, $dadosAtualizacao);
    }
}


