<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoUsuario
{
    public function handle(Request $request, Closure $next, ...$tipos): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }
        $tipoAtual = strtoupper((string) ($user->tipo_usuario ?? ''));
        $tiposPermitidos = array_map('strtoupper', $tipos ?? []);
        if (!empty($tiposPermitidos) && !in_array($tipoAtual, $tiposPermitidos, true)) {
            if ($request->expectsJson()) {
                return response()->json(['sucesso' => false, 'mensagem' => 'Acesso negado'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar esta área.');
        }
        return $next($request);
    }
}



