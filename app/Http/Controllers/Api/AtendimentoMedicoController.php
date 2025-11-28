<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AtendimentoMedico;
use App\Models\Paciente;
use App\Models\Triagem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AtendimentoMedicoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = AtendimentoMedico::with(['paciente','triagem','medico']);
        
        if ($request->has('status')) {
            if ($request->status === 'OBSERVACAO') {
                $query->observacaoAtiva();
            } else {
                $query->where('status', $request->status);
            }
        } else {
            $query->ativos();
        }
        
        if ($request->has('paciente_id')) {
            $query->where('paciente_id', $request->paciente_id);
        }
        
        $result = $query->orderBy('created_at','desc')->paginate(15);
        
        return response()->json([
            'sucesso' => true,
            'dados' => $result
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'paciente_id' => 'required|exists:pacientes,id',
            'triagem_id' => 'nullable|exists:triagens,id',
            'historico_medico' => 'required|string',
            'exame_fisico' => 'nullable|array',
            'diagnosticos' => 'required|array|min:1',
            'exames_solicitados' => 'nullable|array',
            'prescricoes' => 'nullable|array',
            'conduta' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['sucesso'=>false,'mensagem'=>'Dados inválidos','erros'=>$validator->errors()],422);
        }

        $paciente = Paciente::find($request->paciente_id);
        if (!$paciente) {
            return response()->json(['sucesso'=>false,'mensagem'=>'Paciente não encontrado'],404);
        }

        $dados = $request->all();
        $dados['medico_id'] = $request->user()->id;
        $dados['inicio_atendimento'] = now();
        $atendimento = AtendimentoMedico::create($dados);

        if ($request->triagem_id) {
            $triagem = Triagem::find($request->triagem_id);
            if ($triagem) {
                $triagem->update(['status' => 'EM_ANDAMENTO']);
            }
        }

        return response()->json(['sucesso'=>true,'mensagem'=>'Atendimento criado','dados'=>$atendimento->load(['paciente','triagem','medico'])],201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $atendimento = AtendimentoMedico::find($id);
        if (!$atendimento) {
            return response()->json(['sucesso'=>false,'mensagem'=>'Atendimento não encontrado'],404);
        }

        $validator = Validator::make($request->all(), [
            'historico_medico' => 'nullable|string',
            'exame_fisico' => 'nullable|array',
            'diagnosticos' => 'nullable|array',
            'exames_solicitados' => 'nullable|array',
            'prescricoes' => 'nullable|array',
            'conduta' => 'nullable|array',
            'status' => 'nullable|in:EM_ATENDIMENTO,OBSERVACAO,FINALIZADO'
        ]);

        if ($validator->fails()) {
            return response()->json(['sucesso'=>false,'mensagem'=>'Dados inválidos','erros'=>$validator->errors()],422);
        }

        $dados = $request->all();
        $atendimento->update($dados);

        return response()->json(['sucesso'=>true,'mensagem'=>'Atendimento atualizado','dados'=>$atendimento->fresh()->load(['paciente','triagem','medico'])]);
    }

    public function observar(Request $request, string $id): JsonResponse
    {
        $atendimento = AtendimentoMedico::find($id);
        if (!$atendimento) {
            return response()->json(['sucesso'=>false,'mensagem'=>'Atendimento não encontrado'],404);
        }

        $validator = Validator::make($request->all(), [
            'encaminhamento' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['sucesso'=>false,'mensagem'=>'Encaminhamento obrigatório','erros'=>$validator->errors()],422);
        }

        $conduta = $atendimento->conduta ?? [];
        $conduta['encaminhamento'] = $request->encaminhamento;
        $atendimento->update([
            'status' => 'OBSERVACAO',
            'inicio_observacao' => now(),
            'conduta' => $conduta,
        ]);

        if ($atendimento->triagem_id) {
            $triagem = Triagem::find($atendimento->triagem_id);
            if ($triagem) {
                $triagem->update(['status' => 'EM_ANDAMENTO']);
            }
        }

        return response()->json(['sucesso'=>true,'mensagem'=>'Paciente em observação','dados'=>$atendimento->fresh()]);
    }

    public function finalizar(Request $request, string $id): JsonResponse
    {
        $usuario = $request->user();
        $isMedico = strtoupper((string)($usuario->tipo_usuario ?? '')) === 'MEDICO';
        $podeLiberar = (bool)($usuario->permite_liberar_observacao ?? false);
        
        if (!$isMedico && !$podeLiberar) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Você não tem permissão para liberar pacientes.'
            ], 403);
        }
        
        $atendimento = AtendimentoMedico::find($id);
        if (!$atendimento) {
            return response()->json(['sucesso'=>false,'mensagem'=>'Atendimento não encontrado'],404);
        }

        $erros = [];
        if (!is_string($atendimento->historico_medico) || trim((string)$atendimento->historico_medico) === '') {
            $erros['historico_medico'][] = 'Anamnese obrigatória.';
        }
        if (!is_array($atendimento->diagnosticos) || count($atendimento->diagnosticos) < 1) {
            $erros['diagnosticos'][] = 'Diagnóstico obrigatório.';
        }
        if (!is_array($atendimento->conduta) || count($atendimento->conduta) < 1) {
            $erros['conduta'][] = 'Conduta obrigatória.';
        }
        if (!empty($erros)) {
            return response()->json(['sucesso'=>false,'mensagem'=>'Preencha os campos obrigatórios para finalizar o atendimento.','erros'=>$erros],422);
        }

        $payload = [
            'status' => 'FINALIZADO',
            'fim_atendimento' => now(),
        ];
        
        if ($atendimento->status === 'OBSERVACAO') {
            $payload['fim_observacao'] = now();
        }
        
        $atendimento->update($payload);

        if ($atendimento->triagem_id) {
            $triagem = Triagem::find($atendimento->triagem_id);
            if ($triagem) {
                $triagem->update(['status' => 'CONCLUIDA']);
            }
        }

        return response()->json(['sucesso'=>true,'mensagem'=>'Atendimento finalizado','dados'=>$atendimento->fresh()]);
    }
}


