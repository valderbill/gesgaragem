<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Acessos Liberados</title>
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
    <!-- Logo -->
    <div class="logo">
        <img src="{{ public_path('images/logo_atual.png') }}" alt="Logo" style="max-width: 210px;">
    </div>

    <!-- Título do relatório -->
    <h2>Relatório de Acessos Liberados</h2>

    <!-- Filtros aplicados -->
    <div class="filtros">
        @foreach($filtros as $chave => $valor)
            @if(!empty($valor))
                <strong>{{ ucfirst(str_replace('_', ' ', $chave)) }}:</strong> 
                {{ is_array($valor) ? implode(', ', $valor) : $valor }}<br>
            @endif
        @endforeach
    </div>

    <!-- Tabela com os dados -->
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Matrícula</th>
                <th>Status</th>
                <th>Data de Cadastro</th>
                <th>Criado Por</th>
            </tr>
        </thead>
        <tbody>
            @foreach($acessos as $acesso)
                <tr>
                    <td>{{ $acesso->nome }}</td>
                    <td>{{ $acesso->matricula }}</td>
                    <td>{{ $acesso->status == 't' ? 'Ativo' : 'Inativo' }}</td>
                    <td>{{ \Carbon\Carbon::parse($acesso->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ optional($acesso->criador)->nome ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
