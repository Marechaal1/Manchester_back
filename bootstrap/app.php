<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'nocache' => \App\Http\Middleware\NoCache::class,
            'tipo' => \App\Http\Middleware\TipoUsuario::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\App\Domain\Exceptions\DomainException $e, $request) {
            if ($request->expectsJson()) {
                $response = [
                    'sucesso' => false,
                    'mensagem' => $e->getMessage(),
                ];

                if ($e instanceof \App\Infrastructure\Exceptions\ValidationException) {
                    $response['erros'] = $e->getErrors();
                }

                return response()->json($response, $e->getCode() ?: 400);
            }
        });
    })->create();
