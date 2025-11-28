<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\PacienteRepositoryInterface;
use App\Models\Paciente;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PacienteRepository implements PacienteRepositoryInterface
{
    public function paginateAtivos(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Paciente::ativos()
            ->with('triagens')
            ->orderBy('created_at', 'desc');

        $this->aplicarFiltros($query, $filters);

        return $query->paginate($perPage);
    }

    public function create(array $data): Paciente
    {
        return Paciente::create($data);
    }

    public function findAtivoComTriagensUsuario(string $id): ?Paciente
    {
        return Paciente::ativos()->with('triagens.usuario')->find($id);
    }

    public function findById(string $id): ?Paciente
    {
        return Paciente::find($id);
    }

    public function update(Paciente $paciente, array $data): Paciente
    {
        $paciente->update($data);
        return $paciente->fresh();
    }

    public function inativar(Paciente $paciente): void
    {
        $paciente->update(['ativo' => false]);
    }

    public function findAtivoByCpfOrDigits(string $cpf, string $cpfDigits): ?Paciente
    {
        return Paciente::ativos()
            ->where('cpf', $cpf)
            ->orWhereRaw("REPLACE(REPLACE(cpf, '.', ''), '-', '') = ?", [$cpfDigits])
            ->first();
    }

    private function aplicarFiltros($query, array $filters): void
    {
        if (isset($filters['busca'])) {
            $busca = $filters['busca'];
            $query->where(function($q) use ($busca) {
                $q->where('nome_completo', 'like', "%{$busca}%")
                  ->orWhere('cpf', 'like', "%{$busca}%");
            });
        }

        if (isset($filters['sexo'])) {
            $query->where('sexo', $filters['sexo']);
        }
    }
}


