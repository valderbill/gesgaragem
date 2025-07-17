@extends('layouts.app')

@section('content')
    <h1>Detalhes do Veículo</h1>

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

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Placa: {{ $veiculo->placa }}</h5>
            <p class="card-text"><strong>Modelo:</strong> {{ $veiculo->modelo }}</p>
            <p class="card-text"><strong>Tipo:</strong> {{ $veiculo->tipo }}</p>
            <p class="card-text"><strong>Cor:</strong> {{ $veiculo->cor }}</p>
            <p class="card-text"><strong>Marca:</strong> {{ $veiculo->marca }}</p>
            <p class="card-text"><strong>Motorista:</strong>
                @if($veiculo->tipo === 'OFICIAL')
                    ---
                @else
                    {{ $veiculo->acesso->nome ?? '---' }}
                @endif
            </p>
        </div>
    </div>

    <a href="{{ route('veiculos.index') }}" class="btn btn-secondary">Voltar para Lista</a>
    <a href="{{ route('veiculos.edit', $veiculo->id) }}" class="btn btn-warning">Editar</a>
    <form action="{{ route('veiculos.destroy', $veiculo->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
    </form>
@endsection