<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Paciente;

interface PacienteRepositoryInterface
{
    /**
     * Pagina pacientes ativos aplicando filtros comuns.
     *
     * Filtros suportados (chaves no array $filters):
     * - busca: string (nome/cpf)
     * - sexo: string (M|F|O)
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateAtivos(array $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Cria um novo paciente.
     *
     * @param array $data
     * @return Paciente
     */
    public function create(array $data): Paciente;

    /**
     * Busca paciente ativo com triagens do usuário.
     *
     * @param string $id
     * @return Paciente|null
     */
    public function findAtivoComTriagensUsuario(string $id): ?Paciente;

    /**
     * Busca paciente por ID.
     *
     * @param string $id
     * @return Paciente|null
     */
    public function findById(string $id): ?Paciente;

    /**
     * Atualiza paciente.
     *
     * @param Paciente $paciente
     * @param array $data
     * @return Paciente
     */
    public function update(Paciente $paciente, array $data): Paciente;

    /**
     * Inativa paciente (soft delete lógico).
     *
     * @param Paciente $paciente
     * @return void
     */
    public function inativar(Paciente $paciente): void;

    /**
     * Busca paciente ativo por CPF (com máscara) ou somente dígitos.
     *
     * @param string $cpf
     * @param string $cpfDigits
     * @return Paciente|null
     */
    public function findAtivoByCpfOrDigits(string $cpf, string $cpfDigits): ?Paciente;
}


