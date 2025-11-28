<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SAE;
use App\Models\Paciente;
use App\Models\Triagem;
use App\Models\DiagnosticoCipe;
use App\Models\IntervencaoCipe;
use App\Models\ResultadoNoc;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SAEController extends Controller
{
    private function normalizarDadosClinicosPT(array $dc): array
    {
        $map = [
            'bloodPressure' => 'pressao_arterial',
            'heartRate' => 'frequencia_cardiaca',
            'temperature' => 'temperatura',
            'respiratoryRate' => 'frequencia_respiratoria',
            'oxygenSaturation' => 'saturacao_oxigenio',
            'weight' => 'peso',
            'height' => 'altura',
            'symptoms' => 'sintomas',
            'medicalHistory' => 'historico_medico',
        ];
        $resultado = [];
        foreach ($dc as $k => $v) {
            if (isset($map[$k])) {
                $resultado[$map[$k]] = $v;
            } else {
                $resultado[$k] = $v;
            }
        }
        return $resultado;
    }
    public function index(Request $request): JsonResponse
    {
        $query = SAE::with(['paciente', 'triagem', 'usuario']);

        if ($request->has('paciente_id')) {
            $query->where('paciente_id', $request->paciente_id);
        }

        if ($request->has('triagem_id')) {
            $query->where('triagem_id', $request->triagem_id);
        }

        $sae = $query->recentes()->paginate(15);

        $sae->getCollection()->transform(function ($item) {
            if (is_string($item->diagnosticos_enfermagem)) {
                $item->diagnosticos_enfermagem = json_decode($item->diagnosticos_enfermagem, true) ?? [];
            }
            if (is_string($item->intervencoes_enfermagem)) {
                $item->intervencoes_enfermagem = json_decode($item->intervencoes_enfermagem, true) ?? [];
            }
            if (is_string($item->dados_clinicos)) {
                $item->dados_clinicos = json_decode($item->dados_clinicos, true) ?? [];
            }
            if (is_string($item->resultados_esperados_noc ?? null)) {
                $item->resultados_esperados_noc = json_decode($item->resultados_esperados_noc, true) ?? [];
            }
            if (!$item->responsavel_nome && $item->usuario) {
                $item->responsavel_nome = $item->usuario->nome_completo ?? $item->usuario->name ?? 'N/A';
            }
            if (!$item->responsavel_coren && $item->coren) {
                $item->responsavel_coren = $item->coren;
            }
            
            return $item;
        });

        return response()->json([
            'sucesso' => true,
            'dados' => $sae
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        $isEnfermeiro = in_array($user->tipo_usuario, ['ENFERMEIRO', 'TECNICO_ENFERMAGEM']);
        
        $dadosParaValidacao = $request->all();
        if ($isEnfermeiro && empty($dadosParaValidacao['coren']) && $user->coren) {
            $dadosParaValidacao['coren'] = $user->coren;
        }
        
        $validator = Validator::make($dadosParaValidacao, [
            'paciente_id' => 'required|exists:pacientes,id',
            'triagem_id' => 'nullable|exists:triagens,id',
            'dados_clinicos' => 'nullable|array',
            'diagnosticos_enfermagem' => 'nullable|array',
            'intervencoes_enfermagem' => 'nullable|array',
            'resultados_esperados_noc' => 'nullable|array',
            'evolucao_enfermagem' => 'nullable|string',
            'observacoes_adicionais' => 'nullable|string',
            'coren' => $isEnfermeiro ? 'required|string|min:1' : 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Dados inválidos',
                'erros' => $validator->errors()
            ], 422);
        }

        $paciente = Paciente::find($request->paciente_id);
        if (!$paciente) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Paciente não encontrado'
            ], 404);
        }

        $dados = $request->all();
        $dados['usuario_id'] = $request->user()->id;
        $dados['data_registro'] = now();
        
        if (empty($dados['coren']) && $user->coren) {
            $dados['coren'] = $user->coren;
        }
        
        if (!empty($dados['triagem_id'])) {
            $triagem = Triagem::find($dados['triagem_id']);
            if (!$triagem) {
                return response()->json([
                    'sucesso' => false,
                    'mensagem' => 'Triagem não encontrada'
                ], 404);
            }
        }

        if (!empty($dados['diagnosticos_enfermagem']) && is_array($dados['diagnosticos_enfermagem'])) {
            foreach ($dados['diagnosticos_enfermagem'] as $diag) {
                try {
                    DiagnosticoCipe::updateOrCreate(
                        [ 'codigo' => $diag['code'] ?? $diag['codigo'] ?? null, 'titulo' => $diag['title'] ?? $diag['titulo'] ?? '' ],
                        [ 'definicao' => $diag['definition'] ?? $diag['definicao'] ?? null, 'dominio' => $diag['domain'] ?? $diag['dominio'] ?? null, 'categoria' => $diag['category'] ?? $diag['categoria'] ?? null, 'ativo' => true ]
                    );
                } catch (\Throwable $e) {
                }
            }
        }
        if (!empty($dados['intervencoes_enfermagem']) && is_array($dados['intervencoes_enfermagem'])) {
            foreach ($dados['intervencoes_enfermagem'] as $it) {
                try {
                    IntervencaoCipe::updateOrCreate(
                        [ 'codigo' => $it['code'] ?? $it['codigo'] ?? null, 'titulo' => $it['title'] ?? $it['titulo'] ?? '' ],
                        [ 'definicao' => $it['definition'] ?? $it['definicao'] ?? null, 'dominio' => $it['domain'] ?? $it['dominio'] ?? null, 'categoria' => $it['category'] ?? $it['categoria'] ?? null, 'ativo' => true ]
                    );
                } catch (\Throwable $e) {
                }
            }
        }
        if (!empty($dados['resultados_esperados_noc']) && is_array($dados['resultados_esperados_noc'])) {
            foreach ($dados['resultados_esperados_noc'] as $noc) {
                try {
                    ResultadoNoc::updateOrCreate(
                        [ 'codigo' => $noc['code'] ?? $noc['codigo'] ?? null, 'titulo' => $noc['title'] ?? $noc['titulo'] ?? '' ],
                        [ 'definicao' => $noc['definition'] ?? $noc['definicao'] ?? null, 'dominio' => $noc['domain'] ?? $noc['dominio'] ?? null, 'ativo' => true ]
                    );
                } catch (\Throwable $e) {
                }
            }
        }

        $sae = SAE::create($dados);

        if (!empty($dados['triagem_id']) && !empty($dados['dados_clinicos']) && is_array($dados['dados_clinicos'])) {
            try {
                $triagem = Triagem::find($dados['triagem_id']);
                if ($triagem) {
                    $existentes = [];
                    if (is_array($triagem->dados_clinicos)) {
                        $existentes = $triagem->dados_clinicos;
                    } elseif (is_string($triagem->dados_clinicos)) {
                        $existentes = json_decode($triagem->dados_clinicos, true) ?? [];
                    }
                    $atualizadosPT = $this->normalizarDadosClinicosPT($dados['dados_clinicos']);
                    $triagem->dados_clinicos = array_merge($existentes, $atualizadosPT);
                    $triagem->save();
                }
            } catch (\Throwable $e) {
            }
        }

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'SAE registrada com sucesso',
            'dados' => $sae->load(['paciente', 'triagem', 'usuario'])
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $sae = SAE::with(['paciente', 'triagem', 'usuario'])->find($id);

        if (!$sae) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'SAE não encontrada'
            ], 404);
        }

        return response()->json([
            'sucesso' => true,
            'dados' => $sae
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $sae = SAE::find($id);

        if (!$sae) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'SAE não encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'dados_clinicos' => 'nullable|array',
            'diagnosticos_enfermagem' => 'nullable|array',
            'intervencoes_enfermagem' => 'nullable|array',
            'evolucao_enfermagem' => 'nullable|string',
            'observacoes_adicionais' => 'nullable|string',
            'coren' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Dados inválidos',
                'erros' => $validator->errors()
            ], 422);
        }

        $dadosAtualizacao = $request->all();

        if (!empty($dadosAtualizacao['diagnosticos_enfermagem']) && is_array($dadosAtualizacao['diagnosticos_enfermagem'])) {
            foreach ($dadosAtualizacao['diagnosticos_enfermagem'] as $diag) {
                try {
                    DiagnosticoCipe::updateOrCreate(
                        [ 'codigo' => $diag['code'] ?? $diag['codigo'] ?? null, 'titulo' => $diag['title'] ?? $diag['titulo'] ?? '' ],
                        [
                            'definicao' => $diag['definition'] ?? $diag['definicao'] ?? null,
                            'dominio' => $diag['domain'] ?? $diag['dominio'] ?? null,
                            'categoria' => $diag['category'] ?? $diag['categoria'] ?? null,
                            'ativo' => true,
                        ]
                    );
                } catch (\Throwable $e) {
                }
            }
        }

        if (!empty($dadosAtualizacao['intervencoes_enfermagem']) && is_array($dadosAtualizacao['intervencoes_enfermagem'])) {
            foreach ($dadosAtualizacao['intervencoes_enfermagem'] as $it) {
                try {
                    IntervencaoCipe::updateOrCreate(
                        [ 'codigo' => $it['code'] ?? $it['codigo'] ?? null, 'titulo' => $it['title'] ?? $it['titulo'] ?? '' ],
                        [ 'definicao' => $it['definition'] ?? $it['definicao'] ?? null, 'dominio' => $it['domain'] ?? $it['dominio'] ?? null, 'categoria' => $it['category'] ?? $it['categoria'] ?? null, 'ativo' => true ]
                    );
                } catch (\Throwable $e) {
                }
            }
        }

        if (!empty($dadosAtualizacao['resultados_esperados_noc']) && is_array($dadosAtualizacao['resultados_esperados_noc'])) {
            foreach ($dadosAtualizacao['resultados_esperados_noc'] as $noc) {
                try {
                    ResultadoNoc::updateOrCreate(
                        [ 'codigo' => $noc['code'] ?? $noc['codigo'] ?? null, 'titulo' => $noc['title'] ?? $noc['titulo'] ?? '' ],
                        [ 'definicao' => $noc['definition'] ?? $noc['definicao'] ?? null, 'dominio' => $noc['domain'] ?? $noc['dominio'] ?? null, 'ativo' => true ]
                    );
                } catch (\Throwable $e) {
                }
            }
        }
 
        $sae->update($dadosAtualizacao);

        if (!empty($dadosAtualizacao['dados_clinicos']) && is_array($dadosAtualizacao['dados_clinicos']) && !empty($sae->triagem_id)) {
            try {
                $triagem = Triagem::find($sae->triagem_id);
                if ($triagem) {
                    $existentes = [];
                    if (is_array($triagem->dados_clinicos)) {
                        $existentes = $triagem->dados_clinicos;
                    } elseif (is_string($triagem->dados_clinicos)) {
                        $existentes = json_decode($triagem->dados_clinicos, true) ?? [];
                    }
                    $atualizadosPT = $this->normalizarDadosClinicosPT($dadosAtualizacao['dados_clinicos']);
                    $triagem->dados_clinicos = array_merge($existentes, $atualizadosPT);
                    $triagem->save();
                }
            } catch (\Throwable $e) {
            }
        }

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'SAE atualizada com sucesso',
            'dados' => $sae->load(['paciente', 'triagem', 'usuario'])
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $sae = SAE::find($id);

        if (!$sae) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'SAE não encontrada'
            ], 404);
        }

        $sae->delete();

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'SAE removida com sucesso'
        ]);
    }

    public function anterior(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'paciente_id' => 'required|exists:pacientes,id',
            'triagem_id' => 'nullable|exists:triagens,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Dados inválidos',
                'erros' => $validator->errors()
            ], 422);
        }

        $query = SAE::with(['paciente', 'triagem', 'usuario'])
            ->where('paciente_id', $request->paciente_id);

        if ($request->has('triagem_id')) {
            $triagemAtual = Triagem::find($request->triagem_id);
            if ($triagemAtual) {
                $query->where('data_registro', '<', $triagemAtual->data_triagem);
            }
        }

        $saeAnterior = $query->recentes()->first();

        return response()->json([
            'sucesso' => true,
            'dados' => $saeAnterior
        ]);
    }
}