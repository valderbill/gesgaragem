<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Veículos</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo img {
            max-width: 150px;
        }

        h2 {
            text-align: center;
            margin: 0;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .filtros {
            font-size: 10px;
            margin-top: 10px;
        }

        .filtros strong {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="{{ public_path('images/foto.png') }}" alt="Logo" style="max-width: 150px;">
    </div>

    <h2>Relatório de Veículos</h2>

    <div class="filtros">
        @foreach($filtros as $chave => $valor)
            @if(!empty($valor))
                <strong>{{ ucfirst(str_replace('_', ' ', $chave)) }}:</strong> 
                {{ is_array($valor) ? implode(', ', $valor) : $valor }}<br>
            @endif
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th>Placa</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Cor</th>
                <th>Tipo</th>
                <th>Motorista</th>
                <th>Data de Cadastro</th>
                <th>Criado Por</th>
            </tr>
        </thead>
        <tbody>
            @forelse($veiculos as $veiculo)
                <tr>
                    <td>{{ $veiculo->placa }}</td>
                    <td>{{ $veiculo->modelo }}</td>
                    <td>{{ $veiculo->marca }}</td>
                    <td>{{ $veiculo->cor }}</td>
                    <td>{{ $veiculo->tipo }}</td>
                    <td>
                        @if ($veiculo->tipo === 'OFICIAL')
                            {{ optional($veiculo->motoristaOficial)->nome }}
                        @elseif (in_array($veiculo->tipo, ['PARTICULAR', 'MOTO']))
                            {{ optional($veiculo->acesso)->nome }}
                        @else
                            Não identificado
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($veiculo->criado_em)->format('d/m/Y H:i') }}</td>
                    <td>{{ optional($veiculo->criador)->nome ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Nenhum veículo encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
