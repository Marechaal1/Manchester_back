<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Triagem;
use Illuminate\Support\Collection;

interface TriagemRepositoryInterface
{
    /**
     * Pagina triagens aplicando filtros comuns.
     *
     * Filtros suportados (chaves no array $filters):
     * - status: string
     * - classificacao_risco: string
     * - data_inicio: Y-m-d
     * - data_fim: Y-m-d
     * - usuario_id: string
     *
     * Por padrão exclui triagens concluídas e com atendimento finalizado,
     * a menos que explicitamente desabilitado via parâmetros booleanos.
     *
     * @param array $filters
     * @param int $perPage
     * @param bool $excluirConcluidas
     * @param bool $excluirComAtendimentoFinalizado
     * @return LengthAwarePaginator
     */
    public function paginate(array $filters, int $perPage = 15, bool $excluirConcluidas = true, bool $excluirComAtendimentoFinalizado = true): LengthAwarePaginator;

    /**
     * Cria uma nova triagem.
     *
     * @param array $data
     * @return Triagem
     */
    public function create(array $data): Triagem;

    /**
     * Busca triagem por ID carregando relações necessárias.
     *
     * @param string $id
     * @return Triagem|null
     */
    public function findWithRelations(string $id): ?Triagem;

    /**
     * Busca triagem por ID sem carregar relações.
     *
     * @param string $id
     * @return Triagem|null
     */
    public function findById(string $id): ?Triagem;

    /**
     * Atualiza uma triagem existente.
     *
     * @param Triagem $triagem
     * @param array $data
     * @return Triagem
     */
    public function update(Triagem $triagem, array $data): Triagem;

    /**
     * Histórico de triagens por paciente.
     *
     * @param string $pacienteId
     * @return Collection<int,Triagem>
     */
    public function historicoPorPaciente(string $pacienteId): Collection;

    /**
     * Pagina somente triagens ativas, aplicando filtros comuns.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateAtivas(array $filters, int $perPage = 15): LengthAwarePaginator;
}


