<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login do usuário
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Dados inválidos',
                'erros' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Credenciais inválidas'
            ], 401);
        }

        $usuario = Auth::user();
        
        // Verificar se o usuário está ativo
        if (!$usuario->ativo) {
            Auth::logout();
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Usuário inativo'
            ], 403);
        }

        // Atualizar último acesso
        $usuario->update(['ultimo_acesso' => now()]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Login realizado com sucesso',
            'dados' => [
                'usuario' => $usuario,
                'token' => $token,
                'tipo_token' => 'Bearer'
            ]
        ]);
    }

    /**
     * Logout do usuário
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Logout realizado com sucesso'
        ]);
    }

    /**
     * Obter dados do usuário autenticado
     */
    public function me(Request $request): JsonResponse
    {
        $usuario = $request->user();
        
        return response()->json([
            'sucesso' => true,
            'dados' => $usuario
        ]);
    }

    /**
     * Atualizar perfil do usuário
     */
    public function atualizarPerfil(Request $request): JsonResponse
    {
        $usuario = $request->user();
        
        $validator = Validator::make($request->all(), [
            'nome_completo' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'crm' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Dados inválidos',
                'erros' => $validator->errors()
            ], 422);
        }

        $usuario->update($request->only(['nome_completo', 'telefone', 'crm']));

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Perfil atualizado com sucesso',
            'dados' => $usuario->fresh()
        ]);
    }

    /**
     * Alterar senha
     */
    public function alterarSenha(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'senha_atual' => 'required|string',
            'nova_senha' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Dados inválidos',
                'erros' => $validator->errors()
            ], 422);
        }

        $usuario = $request->user();

        if (!Hash::check($request->senha_atual, $usuario->password)) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Senha atual incorreta'
            ], 400);
        }

        $usuario->update([
            'password' => Hash::make($request->nova_senha)
        ]);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Senha alterada com sucesso'
        ]);
    }
}
