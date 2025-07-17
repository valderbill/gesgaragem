<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Motoristas Oficiais</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            margin: 0 20px;
        }

        .logo {
            text-align: center;
            margin-bottom: 5px;
        }

        .logo img {
            max-width: 100px;
        }

        h2 {
            text-align: center;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .filtros {
            font-size: 8px;
            margin-top: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 5px;
        }

        .filtros strong {
            margin-right: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th, td {
            border: 1px solid #000;
            padding: 3px;
            text-align: left;
            font-size: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    {{-- Logo --}}
    <div class="logo">
        <img src="{{ public_path('images/foto.png') }}" alt="Logo">
    </div>

    {{-- Título --}}
    <h2>Relatório de Motoristas Oficiais</h2>

    {{-- Filtros Aplicados --}}
    <div class="filtros">
        @php
            $labels = [
                'nome' => 'Nome',
                'matricula' => 'Matrícula',
                'ativo' => 'Status'
            ];
        @endphp

        @foreach($filtros ?? [] as $chave => $valor)
            @if(!empty($valor) && isset($labels[$chave]))
                <strong>{{ $labels[$chave] }}:</strong>
                @if($chave === 'ativo')
                    {{ $valor === '1' ? 'Ativo' : 'Inativo' }}
                @else
                    {{ $valor }}
                @endif
                <br>
            @endif
        @endforeach
    </div>

    {{-- Tabela --}}
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Matrícula</th>
                <th>Status</th>
                <th>Data de Cadastro</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($motoristas as $motorista)
                <tr>
                    <td>{{ $motorista->nome }}</td>
                    <td>{{ $motorista->matricula }}</td>
                    <td>{{ $motorista->ativo ? 'Ativo' : 'Inativo' }}</td>
                    <td>{{ \Carbon\Carbon::parse($motorista->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhum motorista encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
