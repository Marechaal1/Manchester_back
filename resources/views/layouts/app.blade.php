<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Triagem Manchester')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            /* Cores do sistema - baseadas no app mobile */
            --cor-primaria: #2E86AB;
            --cor-primaria-escura: #1a5f7a;
            --cor-secundaria: #27ae60;
            --cor-aviso: #f39c12;
            --cor-erro: #e74c3c;
            --cor-sucesso: #27ae60;
            --cor-branco: #ffffff;
            --cor-cinza-claro: #f8f9fa;
            --cor-cinza: #6c757d;
            --cor-cinza-escuro: #333333;
            --cor-transparente: rgba(255, 255, 255, 0.2);
            --cor-transparente-claro: rgba(255, 255, 255, 0.3);
            --cor-transparente-medio: rgba(255, 255, 255, 0.7);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--cor-cinza-claro);
        }

        .sidebar {
            min-height: 100vh;
            background: var(--cor-primaria);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link {
            color: var(--cor-transparente-medio);
            border-radius: 0.75rem;
            margin: 0.25rem 0;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .sidebar .nav-link:hover {
            color: var(--cor-branco);
            background-color: var(--cor-transparente);
            transform: translateX(4px);
        }
        
        .sidebar .nav-link.active {
            color: var(--cor-branco);
            background-color: var(--cor-transparente-claro);
            font-weight: 600;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .main-content {
            background-color: var(--cor-cinza-claro);
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            background: var(--cor-branco);
        }
        
        .card-header {
            background: var(--cor-primaria);
            color: var(--cor-branco);
            border-radius: 1rem 1rem 0 0 !important;
            border: none;
            padding: 1.25rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }

        .btn-primary {
            background: var(--cor-primaria);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background: var(--cor-primaria-escura);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(46, 134, 171, 0.3);
        }

        .btn-success {
            background: var(--cor-sucesso);
            border: none;
            border-radius: 0.75rem;
        }
        
        .btn-success:hover {
            background: #229954;
        }

        .btn-warning {
            background: var(--cor-aviso);
            border: none;
            border-radius: 0.75rem;
        }
        
        .btn-warning:hover {
            background: #e67e22;
        }

        .btn-danger {
            background: var(--cor-erro);
            border: none;
            border-radius: 0.75rem;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }

        .table {
            border-radius: 0.75rem;
            overflow: hidden;
        }
        
        .table th {
            background-color: var(--cor-cinza-claro);
            border-top: none;
            font-weight: 600;
            color: var(--cor-cinza-escuro);
            padding: 1rem;
        }
        
        .table td {
            padding: 1rem;
            border-color: rgba(0, 0, 0, 0.05);
        }

        .form-control {
            border-radius: 0.75rem;
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--cor-primaria);
            box-shadow: 0 0 0 0.2rem rgba(46, 134, 171, 0.15);
        }

        .form-select {
            border-radius: 0.75rem;
            border: 1px solid #e0e0e0;
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--cor-sucesso);
        }

        .alert-danger {
            background: rgba(231, 76, 60, 0.1);
            color: var(--cor-erro);
        }

        .badge {
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-weight: 600;
        }

        /* Cores do Protocolo Manchester - Padrão Internacional */
        .badge-vermelho { 
            background-color: #dc3545; /* Vermelho - Emergência Imediata */
            color: var(--cor-branco);
        }
        .badge-laranja { 
            background-color: #fd7e14; /* Laranja - Muito Urgente */
            color: var(--cor-branco);
        }
        .badge-amarelo { 
            background-color: #ffc107; /* Amarelo - Urgente */
            color: var(--cor-cinza-escuro);
        }
        .badge-verde { 
            background-color: #198754; /* Verde - Pouco Urgente */
            color: var(--cor-branco);
        }
        .badge-azul { 
            background-color: #0d6efd; /* Azul - Não Urgente */
            color: var(--cor-branco);
        }

        .page-header {
            background: var(--cor-branco);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .page-title {
            color: var(--cor-cinza-escuro);
            font-weight: 700;
            margin: 0;
        }

        .user-badge {
            background: var(--cor-primaria);
            color: var(--cor-branco);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 500;
        }

        /* Animações suaves */
        .card, .btn, .form-control {
            transition: all 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }
        
        /* Botões somente com ícone: centralização perfeita */
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
            width: 40px;
            height: 40px;
        }
        .btn-group .btn-icon {
            border-radius: 0; /* mantém estética do grupo */
        }
        .btn-group .btn-icon:first-child {
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }
        .btn-group .btn-icon:last-child {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }
        /* Grupo de ações da tabela de pacientes: altura uniforme */
        .pacientes-actions .btn {
            height: 40px;
            display: inline-flex;
            align-items: center;
        }
        .pacientes-actions .btn-icon {
            width: 40px;
        }
        /* Grupos de ícones reutilizáveis */
        .actions-group .btn {
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .actions-group .btn-icon {
            width: 40px;
            padding: 0 !important;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">
                            <i class="fas fa-hospital"></i>
                            Sistema de Triagem
                        </h4>
                        <small class="text-white-50">Protocolo Manchester</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        @php
                            $tipoUsuario = strtoupper(Auth::user()->tipo_usuario ?? '');
                        @endphp
                        @if(in_array($tipoUsuario, ['MEDICO', 'ADMINISTRADOR']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                                    <i class="fas fa-users"></i>
                                    Usuários
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}" href="{{ route('pacientes.index') }}">
                                <i class="fas fa-user-injured"></i>
                                Pacientes
                            </a>
                        </li>
                        @if(in_array($tipoUsuario, ['MEDICO', 'ADMINISTRADOR']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sistema-parametros.*') ? 'active' : '' }}" href="{{ route('sistema-parametros.index') }}">
                                    <i class="fas fa-cogs"></i>
                                    Parâmetros do Sistema
                                </a>
                            </li>
                        @endif
                        @php
                            $user = Auth::user();
                            $podeAcessarRelatorios = $user->tipo_usuario === 'ADMINISTRADOR' || ($user->permite_extrair_relatorios ?? false);
                        @endphp
                        @if($podeAcessarRelatorios)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}" href="{{ route('relatorios.index') }}">
                                    <i class="fas fa-file-excel"></i>
                                    Relatórios
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Sair
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-muted mb-0">@yield('page-subtitle', 'Sistema de Triagem Manchester')</p>
                        </div>
                        <div class="user-badge">
                            <i class="fas fa-user me-2"></i>
                            @auth
                                {{ Auth::user()->nome_completo ?? Auth::user()->name }}
                            @else
                                Visitante
                            @endauth
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('scripts')
</body>
</html>
