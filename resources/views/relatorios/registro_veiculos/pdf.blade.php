<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Registros de Veículos</title>
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
            max-width: 290px;
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
    {{-- Logo do sistema --}}
    <div class="logo">
        <img src="{{ public_path('images/logo_atual.png') }}" alt="Logo">
    </div>

    {{-- Título --}}
    <h2>Relatório de Registros de Veículos</h2>

    {{-- Filtros Aplicados --}}
    <div class="filtros">
        @php
            $labels = [
                'placa' => 'Placa',
                'tipo' => 'Tipo',
                'localizacao' => 'Localização',
                'nome_motorista_entrada' => 'Motorista Entrada',
                'nome_motorista_saida' => 'Motorista Saída',
                'usuario_entrada' => 'Usuário Entrada',
                'usuario_saida' => 'Usuário Saída',
                'data_inicial' => 'Data Inicial (Entrada)',
                'hora_inicial' => 'Hora Inicial (Entrada)',
                'data_final' => 'Data Final (Saída)',
                'hora_final' => 'Hora Final (Saída)',
            ];
        @endphp

        @foreach($filtros as $chave => $valor)
            @if(!empty($valor) && isset($labels[$chave]))
                <strong>{{ $labels[$chave] }}:</strong>
                {{ is_array($valor) ? implode(', ', $valor) : $valor }}<br>
            @endif
        @endforeach
    </div>

    {{-- Tabela de registros --}}
    <table>
        <thead>
            <tr>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Cor</th>
                <th>Tipo</th>
                <th>Localização</th>
                <th>Motorista Entrada</th>
                <th>Motorista Saída</th>
                <th>Entrada</th>
                <th>Saída</th>
                <th>Usuário Entrada</th>
                <th>Usuário Saída</th>
                <th>Passageiros</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registros as $registro)
                <tr>
                    <td>{{ $registro->placa }}</td>
                    <td>{{ $registro->marca }}</td>
                    <td>{{ $registro->modelo }}</td>
                    <td>{{ $registro->cor }}</td>
                    <td>{{ $registro->tipo }}</td>
                    <td>{{ optional($registro->estacionamento)->localizacao ?? '-' }}</td>
                    <td>{{ $registro->nome_motorista_entrada ?? '-' }}</td>
                    <td>{{ $registro->nome_motorista_saida ?? '-' }}</td>
                    <td>{{ $registro->horario_entrada_formatado }}</td>
                    <td>{{ $registro->horario_saida_formatado }}</td>
                    <td>{{ optional($registro->usuarioEntrada)->nome ?? '-' }}</td>
                    <td>{{ optional($registro->usuarioSaida)->nome ?? '-' }}</td>
                    <td>{{ $registro->quantidade_passageiros }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
