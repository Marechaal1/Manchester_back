<?php

declare(strict_types=1);

namespace App\Application\UseCases\Triagem;

use App\Application\DTOs\CriarTriagemDTO;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repositories\PacienteRepositoryInterface;
use App\Domain\Repositories\TriagemRepositoryInterface;
use App\Domain\Services\TempoEsperaService;
use App\Models\Triagem;
use Illuminate\Support\Str;

class CriarTriagemUseCase
{
    public function __construct(
        private TriagemRepositoryInterface $triagemRepository,
        private PacienteRepositoryInterface $pacienteRepository,
        private TempoEsperaService $tempoEsperaService
    ) {
    }

    public function execute(CriarTriagemDTO $dto): Triagem
    {
        $paciente = $this->pacienteRepository->findById($dto->pacienteId);
        
        if (!$paciente || !$paciente->ativo) {
            throw new EntityNotFoundException('Paciente', $dto->pacienteId);
        }

        $protocolo = $this->gerarProtocolo();
        $tempoEspera = $this->tempoEsperaService->calcular($dto->classificacaoRisco);

        $dadosTriagem = [
            'paciente_id' => $dto->pacienteId,
            'usuario_id' => $dto->usuarioId,
            'protocolo' => $protocolo,
            'data_triagem' => now(),
            'classificacao_risco' => $dto->classificacaoRisco,
            'tempo_espera_minutos' => $tempoEspera,
            'dados_clinicos' => $dto->dadosClinicos,
            'diagnosticos_enfermagem' => $dto->diagnosticosEnfermagem,
            'intervencoes_enfermagem' => $dto->intervencoesEnfermagem,
            'avaliacao_seguranca' => $dto->avaliacaoSeguranca,
            'observacoes' => $dto->observacoes,
        ];

        return $this->triagemRepository->create($dadosTriagem);
    }

    private function gerarProtocolo(): string
    {
        return 'TRI-' . date('Ymd') . '-' . Str::upper(Str::random(6));
    }
}


