<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Motoristas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .logo { text-align: center; margin-bottom: 10px; }
        .logo img { max-width: 150px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        th { background-color: #f2f2f2; }
        .filtros { font-size: 10px; margin-top: 10px; }
        .filtros strong { margin-right: 5px; }
    </style>
</head>
<body>
    <div class="logo">
        <img src="{{ public_path('images/logo_atual.png') }}" alt="Logo" style="max-width: 290px;">
    </div>

    <h2>Relatório de Motoristas</h2>

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
                <th>Nome</th>
                <th>Matrícula</th>
                <th>Status</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($motoristas as $motorista)
                <tr>
                    <td>{{ $motorista->nome }}</td>
                    <td>{{ $motorista->matricula }}</td>
                    <td>{{ $motorista->ativo ? 'Ativo' : 'Inativo' }}</td>
                    <td>{{ optional($motorista->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
