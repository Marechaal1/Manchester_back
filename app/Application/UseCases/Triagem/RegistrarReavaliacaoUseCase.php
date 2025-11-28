<?php

declare(strict_types=1);

namespace App\Application\UseCases\Triagem;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repositories\TriagemRepositoryInterface;
use App\Domain\Services\TempoEsperaService;
use App\Models\Triagem;
use Illuminate\Support\Facades\DB;

class RegistrarReavaliacaoUseCase
{
    private const CLASSIFICACOES_VALIDAS = ['VERMELHO', 'LARANJA', 'AMARELO', 'VERDE', 'AZUL'];

    public function __construct(
        private TriagemRepositoryInterface $triagemRepository,
        private TempoEsperaService $tempoEsperaService
    ) {
    }

    public function execute(
        string $triagemId,
        string $usuarioId,
        array $dadosClinicos = [],
        ?string $novaClassificacao = null,
        ?string $justificativa = null
    ): Triagem {
        $triagem = $this->triagemRepository->findById($triagemId);
        
        if (!$triagem) {
            throw new EntityNotFoundException('Triagem', $triagemId);
        }

        $dadosExistentes = is_array($triagem->dados_clinicos) 
            ? $triagem->dados_clinicos 
            : [];

        $dadosAtualizacao = [
            'ultima_reavaliacao' => now(),
            'reavaliacoes_count' => ($triagem->reavaliacoes_count ?? 0) + 1,
            'requer_reavaliacao' => false,
            'data_reavaliacao' => null,
            'dados_clinicos' => array_merge($dadosExistentes, $dadosClinicos),
        ];

        if ($novaClassificacao && $this->isClassificacaoValida($novaClassificacao)) {
            $dadosAtualizacao['classificacao_risco'] = $novaClassificacao;
            $dadosAtualizacao['tempo_espera_minutos'] = $this->tempoEsperaService->calcular($novaClassificacao);
        }

        $triagem = $this->triagemRepository->update($triagem, $dadosAtualizacao);

        $this->registrarHistoricoReavaliacao((string) $triagem->id, $usuarioId, $dadosClinicos, $justificativa);

        return $triagem->fresh();
    }

    private function isClassificacaoValida(string $classificacao): bool
    {
        return in_array($classificacao, self::CLASSIFICACOES_VALIDAS, true);
    }

    private function registrarHistoricoReavaliacao(
        string $triagemId,
        string $usuarioId,
        array $dadosClinicos,
        ?string $justificativa
    ): void {
        DB::table('triagem_reavaliacoes')->insert([
            'triagem_id' => $triagemId,
            'usuario_id' => $usuarioId,
            'dados_clinicos' => json_encode($dadosClinicos),
            'observacoes' => json_encode(['justificativa' => $justificativa]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

