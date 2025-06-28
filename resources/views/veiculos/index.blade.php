@extends('layouts.app')

@section('content')
    <h1>Lista de Veículos</h1>

    {{-- Mensagens de sucesso e erro --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('veiculos.create') }}" class="btn btn-primary mb-3">Cadastrar Novo Veículo</a>

    <table class="table">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Modelo</th>
                <th>Tipo</th>
                <th>Cor</th>
                <th>Marca</th>
                <th>Motorista</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($veiculos as $veiculo)
                <tr>
                    <td>{{ $veiculo->placa }}</td>
                    <td>{{ $veiculo->modelo }}</td>
                    <td>{{ $veiculo->tipo }}</td>
                    <td>{{ $veiculo->cor }}</td>
                    <td>{{ $veiculo->marca }}</td>
                    <td>
                        @if(strtoupper($veiculo->tipo) !== 'OFICIAL')
                            {{ $veiculo->acessoLiberado->nome ?? $veiculo->nome_motorista ?? '---' }}
                        @else
                            ---
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('veiculos.show', $veiculo->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('veiculos.edit', $veiculo->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('veiculos.destroy', $veiculo->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
