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

    <!-- Estilos adicionais das views -->
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Logo à esquerda -->
            <div>
                <img src="{{ asset('images/foto.png') }}" alt="Logo" class="avatar" />
            </div>

            <!-- À direita: Mensagens + Login / Logout -->
            <div class="d-flex align-items-center gap-4">
                <!-- Link para mensagens com ícone de sino e contador -->
                <a href="{{ url('/mensagens') }}" class="position-relative text-decoration-none">
                    <i class="bi bi-bell-fill message-icon"></i>
                    <span class="message-counter" id="messageCount">3</span>
                </a>

                <!-- Botões de login/logout -->
                <div>
                    <button class="btn btn-success btn-sm me-2" id="btnLogin">Login</button>
                    <button class="btn btn-danger btn-sm" id="btnLogout">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Exibe estacionamento atual (se definido) -->
    @if(session('estacionamento_id'))
        @php
            $estacionamentoNome = \App\Models\Estacionamento::find(session('estacionamento_id'))?->nome;
        @endphp

        @if($estacionamentoNome)
            <div class="container mb-3">
                <div class="alert alert-info text-center">
                    Estacionamento atual: <strong>{{ $estacionamentoNome }}</strong>
                </div>
            </div>
        @endif
    @endif

    <!-- Aqui será inserido o conteúdo da view -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Scripts das views -->
    @yield('scripts')
</body>
</html>
