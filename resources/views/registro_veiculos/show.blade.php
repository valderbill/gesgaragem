@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes do Registro de Veículo</h1>

    <table class="table table-bordered">
        <tr>
            <th>Placa</th>
            <td>{{ $registro_veiculo->placa }}</td>
        </tr>
        <tr>
            <th>Marca</th>
            <td>{{ $registro_veiculo->marca }}</td>
        </tr>
        <tr>
            <th>Modelo</th>
            <td>{{ $registro_veiculo->modelo }}</td>
        </tr>
        <tr>
            <th>Cor</th>
            <td>{{ $registro_veiculo->cor }}</td>
        </tr>
        <tr>
            <th>Tipo</th>
            <td>{{ $registro_veiculo->tipo }}</td>
        </tr>
        <tr>
            <th>Motorista Entrada</th>
            <td>{{ $registro_veiculo->motoristaEntrada->nome ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Motorista Saída</th>
            <td>{{ $registro_veiculo->motoristaSaida->nome ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Horário Entrada</th>
            <td>{{ $registro_veiculo->horario_entrada->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Horário Saída</th>
            <td>{{ $registro_veiculo->horario_saida ? $registro_veiculo->horario_saida->format('d/m/Y H:i') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Usuário Entrada</th>
            <td>{{ $registro_veiculo->usuarioEntrada->nome ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Usuário Saída</th>
            <td>{{ $registro_veiculo->usuarioSaida->nome ?? 'N/A' }}</td>
        </tr>
    </table>

    <a href="{{ route('registro_veiculos.index') }}" class="btn btn-secondary">Voltar</a>
    <a href="{{ route('registro_veiculos.edit', $registro_veiculo->id) }}" class="btn btn-primary">Editar</a>

    <form action="{{ route('registro_veiculos.destroy', $registro_veiculo->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Deseja realmente excluir este registro?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Excluir</button>
    </form>
</div>
@endsection
