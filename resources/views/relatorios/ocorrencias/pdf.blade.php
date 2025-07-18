<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Ocorrências</title>
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
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .acompanhamentos {
            margin-top: 3px;
            margin-left: 10px;
            font-style: italic;
            font-size: 7.5px;
        }
    </style>
</head>
<body>
    {{-- Logo --}}
    <div class="logo">
        <img src="{{ public_path('images/foto.png') }}" alt="Logo">
    </div>

    {{-- Título --}}
    <h2>Relatório de Ocorrências</h2>

    {{-- Filtros Aplicados --}}
    <div class="filtros">
        @php
            $labels = [
                'texto' => 'Texto da Ocorrência',
                'usuario' => 'Usuário',
                'possui_acompanhamento' => 'Possui Acompanhamentos?',
                'data_inicial' => 'Data Inicial',
                'data_final' => 'Data Final',
            ];
        @endphp

        @foreach($filtros ?? [] as $chave => $valor)
            @if(!empty($valor) && isset($labels[$chave]))
                <strong>{{ $labels[$chave] }}:</strong>
                {{ is_array($valor) ? implode(', ', $valor) : $valor }}<br>
            @endif
        @endforeach
    </div>

    {{-- Tabela de ocorrências --}}
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Data/Hora</th>
                <th style="width: 20%;">Usuário</th>
                <th style="width: 65%;">Ocorrência e Acompanhamentos</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ocorrencias as $ocorrencia)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($ocorrencia->horario)->format('d/m/Y H:i') }}</td>
                    <td>{{ $ocorrencia->usuario->nome ?? '-' }}</td>
                    <td>
                        <strong>Ocorrência:</strong> {{ $ocorrencia->descricao }} <!-- Alterado para 'descricao' -->

                        @if($ocorrencia->acompanhamentos->count())
                            <div class="acompanhamentos">
                                <strong>Acompanhamentos:</strong>
                                <ul>
                                    @foreach($ocorrencia->acompanhamentos as $acompanhamento)
                                        <li>
                                            {{ \Carbon\Carbon::parse($acompanhamento->horario)->format('d/m/Y H:i') }} –
                                            {{ $acompanhamento->descricao }} <!-- Alterado para 'descricao' -->
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Nenhuma ocorrência encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
