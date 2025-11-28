<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\TriagemRepositoryInterface;
use App\Models\Triagem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TriagemRepository implements TriagemRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15, bool $excluirConcluidas = true, bool $excluirComAtendimentoFinalizado = true): LengthAwarePaginator
    {
        $query = Triagem::with(['paciente', 'usuario', 'atendimentoMedico', 'reavaliacoes']);

        $this->aplicarFiltros($query, $filters);

        if ($excluirConcluidas) {
            $query->where(function($q) {
                $q->where('status', '!=', 'CONCLUIDA')
                  ->orWhereNull('status');
            });
        }

        if ($excluirComAtendimentoFinalizado) {
            $query->whereDoesntHave('atendimentos', function($subQuery) {
                $subQuery->where('status', 'FINALIZADO');
            });
        }

        return $query->orderBy('data_triagem', 'desc')->paginate($perPage);
    }

    public function create(array $data): Triagem
    {
        return Triagem::create($data);
    }

    public function findWithRelations(string $id): ?Triagem
    {
        return Triagem::with(['paciente', 'usuario', 'atendimentoMedico', 'reavaliacoes'])->find($id);
    }

    public function findById(string $id): ?Triagem
    {
        return Triagem::find($id);
    }

    public function update(Triagem $triagem, array $data): Triagem
    {
        $triagem->update($data);
        return $triagem->fresh();
    }

    public function historicoPorPaciente(string $pacienteId): Collection
    {
        return Triagem::with([
            'paciente',
            'usuario',
            'atendimentoMedico',
            'reavaliacoes',
            'atendimentoMedico.medico'
        ])
        ->where('paciente_id', $pacienteId)
        ->orderBy('data_triagem', 'desc')
        ->get();
    }

    public function paginateAtivas(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Triagem::with(['paciente', 'usuario', 'atendimentoMedico', 'reavaliacoes']);

        $this->aplicarFiltros($query, $filters);

        $query->where(function($q) {
            $q->where('status', '!=', 'CONCLUIDA')
              ->orWhereNull('status');
        });

        $query->whereDoesntHave('atendimentoMedico', function($subQuery) {
            $subQuery->where('status', 'FINALIZADO');
        });

        return $query->orderBy('data_triagem', 'desc')->paginate($perPage);
    }

    private function aplicarFiltros($query, array $filters): void
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['classificacao_risco'])) {
            $query->where('classificacao_risco', $filters['classificacao_risco']);
        }

        if (isset($filters['data_inicio'])) {
            $query->whereDate('data_triagem', '>=', $filters['data_inicio']);
        }

        if (isset($filters['data_fim'])) {
            $query->whereDate('data_triagem', '<=', $filters['data_fim']);
        }

        if (isset($filters['usuario_id'])) {
            $query->where('usuario_id', $filters['usuario_id']);
        }
    }
}


