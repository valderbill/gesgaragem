@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Visualização do Relatório: {{ $relatorio->nome }}</h2>

    <p><strong>Tipos selecionados:</strong> {{ implode(', ', $relatorio->tipos) }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Modelo</th>
                <th>Tipo</th>
                <th>Motorista</th>
                <th>Criado em</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($veiculos as $veiculo)
                <tr>
                    <td>{{ $veiculo->placa }}</td>
                    <td>{{ $veiculo->modelo }}</td>
                    <td>{{ $veiculo->tipo }}</td>
                    <td>{{ optional($veiculo->motorista)->nome }}</td>
                    <td>{{ $veiculo->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
