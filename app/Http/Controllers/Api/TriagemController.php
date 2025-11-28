<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Application\DTOs\CriarTriagemDTO;
use App\Application\DTOs\AtualizarTriagemDTO;
use App\Application\DTOs\TriagemFiltersDTO;
use App\Application\UseCases\Triagem\ListarTriagensUseCase;
use App\Application\UseCases\Triagem\CriarTriagemUseCase;
use App\Application\UseCases\Triagem\AtualizarTriagemUseCase;
use App\Application\UseCases\Triagem\ConcluirTriagemUseCase;
use App\Application\UseCases\Triagem\AgendarReavaliacaoUseCase;
use App\Application\UseCases\Triagem\RegistrarReavaliacaoUseCase;
use App\Application\UseCases\Triagem\ObterTriagemUseCase;
use App\Application\UseCases\Triagem\ListarTriagensAtivasUseCase;
use App\Application\UseCases\Triagem\ObterHistoricoTriagensPacienteUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Triagem\CriarTriagemRequest;
use App\Http\Requests\Triagem\AtualizarTriagemRequest;
use App\Http\Requests\Triagem\AgendarReavaliacaoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TriagemController extends Controller
{
    public function __construct(
        private ListarTriagensUseCase $listarTriagensUseCase,
        private CriarTriagemUseCase $criarTriagemUseCase,
        private AtualizarTriagemUseCase $atualizarTriagemUseCase,
        private ConcluirTriagemUseCase $concluirTriagemUseCase,
        private AgendarReavaliacaoUseCase $agendarReavaliacaoUseCase,
        private RegistrarReavaliacaoUseCase $registrarReavaliacaoUseCase,
        private ObterTriagemUseCase $obterTriagemUseCase,
        private ListarTriagensAtivasUseCase $listarTriagensAtivasUseCase,
        private ObterHistoricoTriagensPacienteUseCase $obterHistoricoTriagensPacienteUseCase
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = new TriagemFiltersDTO(
            status: $request->input('status'),
            classificacaoRisco: $request->input('classificacao_risco'),
            dataInicio: $request->input('data_inicio'),
            dataFim: $request->input('data_fim'),
            usuarioId: $request->input('usuario_id'),
            excluirConcluidas: true,
            excluirComAtendimentoFinalizado: true
        );

        $perPage = (int) $request->get('per_page', 15);
        $triagens = $this->listarTriagensUseCase->execute($filters, $perPage);

        return response()->json([
            'sucesso' => true,
            'dados' => $triagens
        ]);
    }

    public function store(CriarTriagemRequest $request): JsonResponse
    {
        $dto = new CriarTriagemDTO(
            pacienteId: $request->input('paciente_id'),
            classificacaoRisco: $request->input('classificacao_risco'),
            usuarioId: (string) $request->user()->id,
            dadosClinicos: $request->input('dados_clinicos'),
            diagnosticosEnfermagem: $request->input('diagnosticos_enfermagem'),
            intervencoesEnfermagem: $request->input('intervencoes_enfermagem'),
            avaliacaoSeguranca: $request->input('avaliacao_seguranca'),
            observacoes: $request->input('observacoes')
        );

        $triagem = $this->criarTriagemUseCase->execute($dto);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Triagem criada com sucesso',
            'dados' => $triagem->load(['paciente', 'usuario'])
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $triagem = $this->obterTriagemUseCase->execute($id, true);

        return response()->json([
            'sucesso' => true,
            'dados' => $triagem
        ]);
    }

    public function update(AtualizarTriagemRequest $request, string $id): JsonResponse
    {
        $dto = new AtualizarTriagemDTO(
            classificacaoRisco: $request->input('classificacao_risco'),
            dadosClinicos: $request->input('dados_clinicos'),
            diagnosticosEnfermagem: $request->input('diagnosticos_enfermagem'),
            intervencoesEnfermagem: $request->input('intervencoes_enfermagem'),
            avaliacaoSeguranca: $request->input('avaliacao_seguranca'),
            observacoes: $request->input('observacoes'),
            status: $request->input('status')
        );

        $triagem = $this->atualizarTriagemUseCase->execute($id, $dto);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Triagem atualizada com sucesso',
            'dados' => $triagem->load(['paciente', 'usuario'])
        ]);
    }

    public function concluir(Request $request, string $id): JsonResponse
    {
        $triagem = $this->concluirTriagemUseCase->execute($id);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Triagem concluída com sucesso',
            'dados' => $triagem->fresh()
        ]);
    }

    public function agendarReavaliacao(AgendarReavaliacaoRequest $request, string $id): JsonResponse
    {
        $dataReavaliacao = Carbon::parse($request->input('data_reavaliacao'));
        $triagem = $this->agendarReavaliacaoUseCase->execute($id, $dataReavaliacao);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Reavaliação agendada com sucesso',
            'dados' => $triagem->fresh()
        ]);
    }

    public function registrarReavaliacao(Request $request, string $id): JsonResponse
    {
        $triagem = $this->registrarReavaliacaoUseCase->execute(
            $id,
            (string) $request->user()->id,
            $request->input('dados_clinicos', []),
            $request->input('classificacao_risco'),
            $request->input('justificativa')
        );

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Reavaliação registrada com sucesso',
            'dados' => $triagem
        ]);
    }

    public function triagensAtivas(Request $request): JsonResponse
    {
        $filters = new TriagemFiltersDTO(
            status: $request->input('status'),
            classificacaoRisco: $request->input('classificacao_risco'),
            dataInicio: $request->input('data_inicio'),
            dataFim: $request->input('data_fim'),
            usuarioId: $request->input('usuario_id'),
            excluirConcluidas: true,
            excluirComAtendimentoFinalizado: true
        );

        $perPage = (int) $request->get('per_page', 15);
        $triagens = $this->listarTriagensAtivasUseCase->execute($filters, $perPage);

        return response()->json([
            'sucesso' => true,
            'dados' => $triagens
        ]);
    }

    public function historicoTriagensPaciente(string $pacienteId): JsonResponse
    {
        $triagens = $this->obterHistoricoTriagensPacienteUseCase->execute($pacienteId);

        return response()->json([
            'sucesso' => true,
            'dados' => $triagens
        ]);
    }
}
