<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Domain\Repositories\TriagemRepositoryInterface;
use App\Domain\Repositories\PacienteRepositoryInterface;
use App\Infrastructure\Repositories\TriagemRepository;
use App\Infrastructure\Repositories\PacienteRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TriagemRepositoryInterface::class, TriagemRepository::class);
        $this->app->bind(PacienteRepositoryInterface::class, PacienteRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Usar o template de paginação do Bootstrap 5
        Paginator::useBootstrapFive();

        // Gate para gerenciar usuários
        Gate::define('gerenciar-usuarios', function (?User $user) {
            if (!$user) {
                return false;
            }
            // Administrador sempre pode
            if (strtoupper((string)$user->tipo_usuario) === 'ADMINISTRADOR') {
                return true;
            }
            // Enfermeiro apenas com flag habilitada
            if (strtoupper((string)$user->tipo_usuario) === 'ENFERMEIRO') {
                return (bool) $user->permite_gerenciar_usuarios;
            }
            return false;
        });

        // Gate para liberar pacientes em observação
        Gate::define('liberar-observacao', function (?User $user) {
            if (!$user) {
                return false;
            }
            // Administrador e Médico podem liberar
            $tipo = strtoupper((string)$user->tipo_usuario);
            if (in_array($tipo, ['ADMINISTRADOR', 'MEDICO'])) {
                return true;
            }
            // Enfermeiro apenas com flag habilitada
            if ($tipo === 'ENFERMEIRO') {
                return (bool) $user->permite_liberar_observacao;
            }
            return false;
        });
    }
}
