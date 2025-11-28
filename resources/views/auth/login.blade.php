<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistema de Triagem Manchester</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --cor-primaria: #2E86AB;
            --cor-primaria-escura: #1a5f7a;
            --cor-branco: #ffffff;
            --cor-cinza-claro: #f8f9fa;
            --cor-cinza: #6c757d;
            --cor-cinza-escuro: #333333;
        }

        body {
            background: var(--cor-primaria);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .login-container {
            background: var(--cor-branco);
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .login-header {
            background: var(--cor-primaria);
            color: var(--cor-branco);
            padding: 2.5rem 2rem;
            text-align: center;
        }
        
        .login-header h3 {
            font-weight: 700;
            margin: 1rem 0 0.5rem 0;
        }
        
        .login-header p {
            opacity: 0.9;
            margin: 0;
        }
        
        .login-body {
            padding: 2.5rem 2rem;
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
        
        .btn-login {
            background: var(--cor-primaria);
            border: none;
            border-radius: 0.75rem;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-login:hover {
            background: var(--cor-primaria-escura);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(46, 134, 171, 0.3);
        }
        
        .input-group-text {
            background: var(--cor-cinza-claro);
            border: 1px solid #e0e0e0;
            border-right: none;
            border-radius: 0.75rem 0 0 0.75rem;
            color: var(--cor-cinza);
        }
        
        .form-control {
            border-left: none;
            border-radius: 0 0.75rem 0.75rem 0;
        }
        
        .form-control:focus {
            border-left: none;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--cor-cinza-escuro);
            margin-bottom: 0.5rem;
        }
        
        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.25rem;
        }
        
        .alert-danger {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }
        
        .security-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-container">
                    <div class="login-header">
                        <i class="fas fa-hospital fa-3x mb-3"></i>
                        <h3>Sistema de Triagem</h3>
                        <p class="mb-0">Protocolo Manchester</p>
                    </div>
                    
                    <div class="login-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Entrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p class="security-text">
                        <i class="fas fa-shield-alt me-2"></i>
                        Sistema seguro e confiável
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
