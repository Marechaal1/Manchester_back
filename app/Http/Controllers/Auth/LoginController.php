<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            
            // Verificar se o usuário está ativo
            if (!$user->ativo) {
                Auth::guard('web')->logout();
                return redirect()->back()
                    ->withErrors(['email' => 'Usuário inativo. Entre em contato com o administrador.'])
                    ->withInput();
            }

            // Atualizar último acesso
            $user->update(['ultimo_acesso' => now()]);

            // Regenerar a sessão para evitar fixation e garantir cookie válido
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Credenciais inválidas.'])
            ->withInput();
    }
}
