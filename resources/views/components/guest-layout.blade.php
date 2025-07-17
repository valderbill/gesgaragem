<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Estacionamento</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(to right, #003b5c, #005b8f);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center font-sans antialiased">
    <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg">
        <div class="text-center mb-6">
            {{-- Se quiser usar uma imagem, substitua a linha abaixo por: <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto mb-4 w-20"> --}}
            <h1 class="text-xl font-semibold text-gray-800">Sistema de Estacionamento</h1>
            <p class="text-sm text-gray-500">Acesso restrito</p>
        </div>

        {{-- Formulário será injetado aqui --}}
        {{ $slot }}
    </div>
</body>
</html>
