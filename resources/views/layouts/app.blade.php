<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Controle Estacionamento</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Estilo Digital -->
    <link href="{{ asset('css/digital.css') }}" rel="stylesheet">
    @yield('styles')

    <style>
        .avatar {
            width: 50px;
            height: auto;
            border-radius: 10px;
            object-fit: contain;
            background-color: #f8f9fa;
            display: block;
        }

        .message-icon {
            font-size: 1.8rem;
            color: #0d6efd;
        }

        .message-counter {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            font-size: 0.75rem;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Navbar principal -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/selecionar-estacionamento') }}">
                <img src="{{ asset('images/foto.png') }}" alt="Logo" class="avatar me-2">
                <strong>Sistema Estacionamento</strong>
            </a>

            @auth
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @php
                        $perfil = strtolower(Auth::user()?->perfil?->nome);
                    @endphp

                    @if($perfil === 'administrador')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Administração
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('usuarios.index') }}">Usuários</a></li>
                            <li><a class="dropdown-item" href="{{ route('motoristas.index') }}">Motoristas</a></li>
                            <li><a class="dropdown-item" href="{{ route('veiculos.index') }}">Veículos</a></li>
                            <li><a class="dropdown-item" href="{{ route('registro_veiculos.index') }}">Registro de Veículos</a></li>
                            <li><a class="dropdown-item" href="{{ route('acessos_liberados.index') }}">Acessos Liberados</a></li>
                            <li><a class="dropdown-item" href="{{ route('estacionamentos.index') }}">Estacionamentos</a></li>
                            <li><a class="dropdown-item" href="{{ route('ocorrencias.index') }}">Ocorrências</a></li>
                        </ul>
                    </li>
                    @elseif(in_array($perfil, ['vigilante', 'recepcionista']))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Vigilante/Recepção
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('registro_veiculos.index') }}">Registro de Veículos</a></li>
                            <li><a class="dropdown-item" href="{{ route('motoristas.index') }}">Motoristas</a></li>
                            <li><a class="dropdown-item" href="{{ route('veiculos.index') }}">Veículos</a></li>
                            <li><a class="dropdown-item" href="{{ route('ocorrencias.index') }}">Minhas Ocorrências</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
            @endauth

            <!-- Direita: Notificações e usuário -->
            <div class="d-flex align-items-center gap-4">
                @auth
                    @php
                        $mensagensNaoLidas = Auth::user()->mensagensNaoLidas()->count();
                    @endphp

                    <a href="{{ url('/mensagens') }}" class="position-relative text-decoration-none">
                        <i class="bi bi-bell-fill message-icon"></i>
                        @if($mensagensNaoLidas > 0)
                            <span class="message-counter" id="messageCount">{{ $mensagensNaoLidas }}</span>
                        @endif
                    </a>

                    <a href="{{ url('/dashboard') }}" class="text-decoration-none" title="Painel de Controle">
                        <i class="bi bi-record-circle"></i>
                    </a>

                    <span class="me-2">Bem-vindo, <strong>{{ Auth::user()->nome ?? Auth::user()->matricula }}</strong></span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-success btn-sm">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Conteúdo das páginas -->
    <div class="container">
        @yield('content')
    </div>

    @yield('scripts')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
