<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Controle Estacionamento</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- CSS personalizado -->
    <link href="{{ asset('css/digital.css') }}" rel="stylesheet">

    @yield('styles')

    <style>
        .avatar {
            width: 250px;
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
            <!-- Logo e título -->
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/selecionar-estacionamento') }}">
                <img src="{{ asset('images/logo_atual.png') }}" alt="Logo" class="avatar me-2">
                <strong>Sistema Estacionamento</strong>
            </a>

            <!-- Menu por perfil -->
            @auth
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @php
                        $perfil = strtolower(Auth::user()->perfil->nome ?? '');
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

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Relatórios
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('relatorios.motoristas.index') }}">Motoristas</a></li>
                            <li><a class="dropdown-item" href="{{ route('relatorios.ocorrencias.index') }}">Ocorrências</a></li>
                            <li><a class="dropdown-item" href="{{ route('relatorios.veiculos.index') }}">Veículos</a></li>
                            <li><a class="dropdown-item" href="{{ route('relatorios.usuarios.index') }}">Usuários</a></li>
                            <li><a class="dropdown-item" href="{{ route('relatorios.registros.index') }}">Registros de Veículos</a></li>
                            <li><a class="dropdown-item" href="{{ route('relatorios.acessos_liberados.index') }}">Acessos Liberados</a></li>
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

            <!-- Direita: Notificações, painel e perfil -->
            <div class="d-flex align-items-center gap-4">
                @auth
                    @php
                        $mensagensNaoLidas = Auth::user()->mensagensNaoLidas()->count();
                    @endphp

                    <!-- Notificações -->
                    <a href="{{ url('/mensagens') }}" class="position-relative text-decoration-none" title="Mensagens">
                        <i class="bi bi-bell-fill message-icon"></i>
                        @if($mensagensNaoLidas > 0)
                            <span class="message-counter" id="messageCount">{{ $mensagensNaoLidas }}</span>
                        @endif
                    </a>

                    <!-- Painel -->
                    <a href="{{ url('/dashboard') }}" class="text-decoration-none" title="Painel de Controle">
                        <i class="bi bi-record-circle fs-4 text-success"></i>
                    </a>

                    <!-- Nome e dropdown -->
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold text-dark" href="#" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->nome ?? Auth::user()->matricula }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                           <!-- <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>-->
                            <li><a class="dropdown-item" href="{{ route('senha.edit') }}">Alterar Senha</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Sair</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Login para visitantes -->
                    <a href="{{ route('login') }}" class="btn btn-success btn-sm">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Scripts adicionais -->
    @yield('scripts')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
