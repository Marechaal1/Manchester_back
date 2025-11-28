<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Application\DTOs\PacienteFiltersDTO;
use App\Application\UseCases\Paciente\ListarPacientesUseCase;
use App\Application\UseCases\Paciente\CriarPacienteUseCase;
use App\Application\UseCases\Paciente\AtualizarPacienteUseCase;
use App\Application\UseCases\Paciente\ObterPacienteUseCase;
use App\Application\UseCases\Paciente\InativarPacienteUseCase;
use App\Application\UseCases\Paciente\BuscarPacientePorCpfUseCase;
use App\Domain\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Paciente\CriarPacienteRequest;
use App\Http\Requests\Paciente\AtualizarPacienteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function __construct(
        private ListarPacientesUseCase $listarPacientesUseCase,
        private CriarPacienteUseCase $criarPacienteUseCase,
        private AtualizarPacienteUseCase $atualizarPacienteUseCase,
        private ObterPacienteUseCase $obterPacienteUseCase,
        private InativarPacienteUseCase $inativarPacienteUseCase,
        private BuscarPacientePorCpfUseCase $buscarPacientePorCpfUseCase
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = new PacienteFiltersDTO(
            busca: $request->input('busca'),
            sexo: $request->input('sexo')
        );

        $perPage = (int) $request->get('per_page', 15);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        $pacientes = $this->listarPacientesUseCase->execute($filters, $perPage);

        return response()->json([
            'sucesso' => true,
            'dados' => $pacientes
        ]);
    }

    public function store(CriarPacienteRequest $request): JsonResponse
    {
        $this->verificarPermissaoCadastro($request->user());

        $paciente = $this->criarPacienteUseCase->execute($request->validated());

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Paciente criado com sucesso',
            'dados' => $paciente
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $paciente = $this->obterPacienteUseCase->execute($id);

        return response()->json([
            'sucesso' => true,
            'dados' => $paciente
        ]);
    }

    public function update(AtualizarPacienteRequest $request, string $id): JsonResponse
    {
        $this->verificarPermissaoAtualizacao($request->user());

        $paciente = $this->atualizarPacienteUseCase->execute($id, $request->validated());

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Paciente atualizado com sucesso',
            'dados' => $paciente
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->inativarPacienteUseCase->execute($id);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Paciente inativado com sucesso'
        ]);
    }

    public function buscarPorCpf(string $cpf): JsonResponse
    {
        $paciente = $this->buscarPacientePorCpfUseCase->execute($cpf);

        return response()->json([
            'sucesso' => true,
            'dados' => $paciente
        ]);
    }

    private function verificarPermissaoCadastro($usuario): void
    {
        $tipo = strtoupper((string)($usuario->tipo_usuario ?? ''));
        if (!in_array($tipo, ['ENFERMEIRO', 'MEDICO', 'ADMINISTRADOR'], true)) {
            throw new UnauthorizedException('Você não tem permissão para cadastrar pacientes.');
        }
    }

    private function verificarPermissaoAtualizacao($usuario): void
    {
        $tipo = strtoupper((string)($usuario->tipo_usuario ?? ''));
        $enfermeiroPode = ($tipo === 'ENFERMEIRO') && (bool)($usuario->permite_gerenciar_pacientes ?? false);
        
        if (!in_array($tipo, ['MEDICO', 'ADMINISTRADOR'], true) && !$enfermeiroPode) {
            throw new UnauthorizedException('Você não tem permissão para atualizar dados de pacientes.');
        }
    }
}
