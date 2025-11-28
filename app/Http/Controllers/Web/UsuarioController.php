<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AtendimentoMedico;
use App\Models\SAE;
use App\Models\TriagemReavaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::paginate(15);
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nome_completo' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:users',
            'data_nascimento' => 'required|date|before:today',
            'sexo' => 'required|in:MASCULINO,FEMININO,OUTRO',
            'estado_civil' => 'required|in:SOLTEIRO,CASADO,DIVORCIADO,VIUVO,UNIAO_ESTAVEL',
            'telefone' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'cep' => 'nullable|string|max:10',
            'endereco' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'crm' => 'required_if:tipo_usuario,MEDICO|nullable|string|max:20',
            'coren' => 'required_if:tipo_usuario,ENFERMEIRO|nullable|string|max:30',
            'tipo_usuario' => 'required|in:ENFERMEIRO,MEDICO,ADMINISTRADOR',
            'permite_gerenciar_usuarios' => 'nullable|boolean',
            'permite_liberar_observacao' => 'nullable|boolean',
            'permite_extrair_relatorios' => 'nullable|boolean'
        ]);

        $usuario = User::create([
            'name' => $request->name,
            'sobrenome' => $request->sobrenome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nome_completo' => $request->nome_completo,
            'cpf' => $request->cpf,
            'data_nascimento' => $request->data_nascimento,
            'sexo' => $request->sexo,
            'estado_civil' => $request->estado_civil,
            'telefone' => $request->telefone,
            'celular' => $request->celular,
            'cep' => $request->cep,
            'endereco' => $request->endereco,
            'numero' => $request->numero,
            'complemento' => $request->complemento,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'crm' => $request->tipo_usuario === 'MEDICO' ? $request->crm : null,
            'coren' => $request->tipo_usuario === 'ENFERMEIRO' ? $request->coren : null,
            'tipo_usuario' => $request->tipo_usuario,
            'ativo' => true,
            'permite_gerenciar_pacientes' => $request->tipo_usuario === 'ENFERMEIRO'
                ? (bool)$request->boolean('permite_gerenciar_pacientes', false)
                : false,
            'permite_liberar_observacao' => $request->tipo_usuario === 'ENFERMEIRO'
                ? (bool)$request->boolean('permite_liberar_observacao', false)
                : false,
            'permite_extrair_relatorios' => in_array($request->tipo_usuario, ['ENFERMEIRO', 'MEDICO'])
                ? (bool)$request->boolean('permite_extrair_relatorios', false)
                : false
        ]);

        // Perfis removidos do cadastro: agora usamos apenas tipo_usuario e permissões específicas

        return redirect()->route('usuarios.create.direct')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'nome_completo' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'max:14', Rule::unique('users')->ignore($usuario->id)],
            'data_nascimento' => 'required|date|before:today',
            'sexo' => 'required|in:MASCULINO,FEMININO,OUTRO',
            'estado_civil' => 'required|in:SOLTEIRO,CASADO,DIVORCIADO,VIUVO,UNIAO_ESTAVEL',
            'telefone' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'cep' => 'nullable|string|max:10',
            'endereco' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'crm' => 'required_if:tipo_usuario,MEDICO|nullable|string|max:20',
            'coren' => 'required_if:tipo_usuario,ENFERMEIRO|nullable|string|max:30',
            'tipo_usuario' => 'required|in:ENFERMEIRO,MEDICO,ADMINISTRADOR',
            'perfis' => 'nullable|array',
            'perfis.*' => 'exists:perfis,id',
            'permite_extrair_relatorios' => 'nullable|boolean'
        ]);

        $data = $request->except('password');
        
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        // Atualiza flags de permissão específicas de ENFERMEIRO
        $data['permite_gerenciar_pacientes'] = $request->tipo_usuario === 'ENFERMEIRO'
            ? (bool)$request->boolean('permite_gerenciar_pacientes', false)
            : false;
        $data['permite_liberar_observacao'] = $request->tipo_usuario === 'ENFERMEIRO'
            ? (bool)$request->boolean('permite_liberar_observacao', false)
            : false;
        // Atualiza flag de permissão para ENFERMEIRO e MEDICO
        $data['permite_extrair_relatorios'] = in_array($request->tipo_usuario, ['ENFERMEIRO', 'MEDICO'])
            ? (bool)$request->boolean('permite_extrair_relatorios', false)
            : false;

        $usuario->update($data);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        // Verificar se há dependências antes de deletar
        $temAtendimentos = AtendimentoMedico::where('medico_id', $usuario->id)->exists();
        $temSAE = SAE::where('usuario_id', $usuario->id)->exists();
        $temReavaliacoes = TriagemReavaliacao::where('usuario_id', $usuario->id)->exists();
        
        if ($temAtendimentos || $temSAE || $temReavaliacoes) {
            $mensagens = [];
            if ($temAtendimentos) {
                $mensagens[] = 'atendimentos médicos';
            }
            if ($temSAE) {
                $mensagens[] = 'registros de SAE';
            }
            if ($temReavaliacoes) {
                $mensagens[] = 'reavaliações de triagem';
            }
            
            $mensagem = 'Não é possível excluir este usuário porque existem ' . implode(', ', $mensagens) . ' associados a ele.';
            
            return redirect()->route('usuarios.index')
                ->with('error', $mensagem);
        }
        
        $usuario->delete();
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário removido com sucesso!');
    }

    /**
     * Ativar usuário
     */
    public function ativar(User $usuario)
    {
        $usuario->update(['ativo' => true]);
        return redirect()->back()
            ->with('success', 'Usuário ativado com sucesso!');
    }

    /**
     * Inativar usuário
     */
    public function inativar(User $usuario)
    {
        $usuario->update(['ativo' => false]);
        return redirect()->back()
            ->with('success', 'Usuário inativado com sucesso!');
    }
}
