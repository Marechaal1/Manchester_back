<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\Triagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    /**
     * Lista pacientes para gestão (todos os pacientes, com última triagem carregada se houver)
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'nome');
        $dir = strtolower($request->input('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        $allowed = ['id', 'nome', 'cpf', 'idade', 'telefone', 'ultima_triagem', 'classificacao'];
        if (!in_array($sort, $allowed, true)) {
            $sort = 'nome';
        }

        // Subconsulta: última triagem por paciente
        $subUltima = Triagem::selectRaw('paciente_id, MAX(created_at) as ultima_triagem')
            ->groupBy('paciente_id');

        $query = Paciente::query()
            ->with(['triagens' => function ($q) {
                $q->orderByDesc('created_at');
            }])
            ->leftJoinSub($subUltima, 'lt', function ($join) {
                $join->on('lt.paciente_id', '=', 'pacientes.id');
            })
            ->leftJoin('triagens as t', function ($join) {
                $join->on('t.paciente_id', '=', 'pacientes.id')
                     ->on('t.created_at', '=', 'lt.ultima_triagem');
            })
            ->select('pacientes.*', DB::raw('lt.ultima_triagem'), DB::raw('t.classificacao_risco as ultima_classificacao'));

        switch ($sort) {
            case 'id':
                $query->orderBy('pacientes.id', $dir);
                break;
            case 'nome':
                $query->orderBy('pacientes.nome_completo', $dir);
                break;
            case 'cpf':
                $query->orderBy('pacientes.cpf', $dir);
                break;
            case 'telefone':
                $query->orderBy('pacientes.telefone', $dir);
                break;
            case 'idade':
                // Idade é inverso de data_nascimento
                $query->orderBy('pacientes.data_nascimento', $dir === 'asc' ? 'desc' : 'asc');
                break;
            case 'ultima_triagem':
                $query->orderBy('lt.ultima_triagem', $dir);
                break;
            case 'classificacao':
                $query->orderBy('t.classificacao_risco', $dir);
                break;
        }

        $pacientes = $query->paginate(15)->withQueryString();

        return view('pacientes.index', [
            'pacientes' => $pacientes,
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    /**
     * Formulário de criação de paciente
     */
    public function create()
    {
        return view('pacientes.create');
    }

    /**
     * Persiste novo paciente
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:pacientes,cpf',
            'data_nascimento' => 'required|date|before:today',
            'sexo' => 'required|in:MASCULINO,FEMININO,OUTRO,M,F,O',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'nome_responsavel' => 'nullable|string|max:255',
            'telefone_responsavel' => 'nullable|string|max:20',
            'observacoes' => 'nullable|string'
        ]);

        // Normaliza 'sexo' para M | F | O
        $sexo = strtoupper((string)($validated['sexo'] ?? ''));
        $map = [
            'M' => 'M', 'MASCULINO' => 'M',
            'F' => 'F', 'FEMININO' => 'F',
            'O' => 'O', 'OUTRO' => 'O',
        ];
        if (isset($map[$sexo])) {
            $validated['sexo'] = $map[$sexo];
        } else {
            return back()->withErrors(['sexo' => 'Sexo inválido.'])->withInput();
        }

        $filtered = array_filter($validated, function ($value) {
            return !is_null($value) && $value !== '';
        });

        $paciente = Paciente::create($filtered);

        return redirect()->route('pacientes.show', $paciente)
            ->with('success', 'Paciente criado com sucesso!');
    }

    /**
     * Mostra dados cadastrais e triagens do paciente
     */
    public function show(Paciente $paciente)
    {
        $paciente->load(['triagens' => function ($q) {
            $q->orderByDesc('created_at');
        }]);

        return view('pacientes.show', compact('paciente'));
    }

    /**
     * Histórico de triagens do paciente (paginado)
     */
    public function historico(Paciente $paciente)
    {
        $triagens = $paciente->triagens()
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('pacientes.historico', compact('paciente', 'triagens'));
    }

    /**
     * Detalhes da triagem do paciente (dados clínicos, SAE e atendimento médico)
     */
    public function triagem(Paciente $paciente, Triagem $triagem)
    {
        // Garantir que a triagem pertence ao paciente
        abort_if($triagem->paciente_id !== $paciente->id, 404);

        $triagem->load(['atendimentoMedico', 'reavaliacoes']);

        return view('pacientes.triagem', [
            'paciente' => $paciente,
            'triagem' => $triagem,
            'atendimento' => $triagem->atendimentoMedico,
        ]);
    }

    /**
     * Formulário para completar/editar dados do paciente
     */
    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    /**
     * Atualiza dados cadastrais do paciente
     */
    public function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'cpf' => 'required|string|max:14',
            'data_nascimento' => 'nullable|date|before:today',
            'sexo' => 'nullable|in:MASCULINO,FEMININO,OUTRO',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'nome_responsavel' => 'nullable|string|max:255',
            'telefone_responsavel' => 'nullable|string|max:20',
            'observacoes' => 'nullable|string'
        ]);

        // Normaliza valor de sexo para o esperado no banco: 'M' | 'F' | 'O'
        if (array_key_exists('sexo', $validated)) {
            $sexo = strtoupper((string)$validated['sexo']);
            $map = [
                'M' => 'M', 'MASCULINO' => 'M',
                'F' => 'F', 'FEMININO' => 'F',
                'O' => 'O', 'OUTRO' => 'O',
            ];
            if (isset($map[$sexo])) {
                $validated['sexo'] = $map[$sexo];
            } else {
                // Se vier um valor inválido, não altera o sexo existente
                unset($validated['sexo']);
            }
        }

        // Evita sobrescrever colunas NOT NULL (ex.: sexo, data_nascimento) com null/vazio
        $filtered = array_filter($validated, function ($value) {
            return !is_null($value) && $value !== '';
        });

        $paciente->fill($filtered);
        $paciente->save();

        return redirect()->route('pacientes.show', $paciente)
            ->with('success', 'Dados do paciente atualizados com sucesso!');
    }
}
