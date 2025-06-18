@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalhes do Estacionamento</h1>

    <div class="mb-3">
        <strong>ID:</strong> {{ $estacionamento->id }}
    </div>
    <div class="mb-3">
        <strong>Localização:</strong> {{ $estacionamento->localizacao ?? 'Não informada' }}
    </div>
    <div class="mb-3">
        <strong>Vagas Particulares:</strong> {{ $estacionamento->vagas_particulares ?? 0 }}
    </div>
    <div class="mb-3">
        <strong>Vagas Oficiais:</strong> {{ $estacionamento->vagas_oficiais ?? 0 }}
    </div>
    <div class="mb-3">
        <strong>Vagas Motos:</strong> {{ $estacionamento->vagas_motos ?? 0 }}
    </div>

    <a href="{{ route('estacionamentos.index') }}" class="btn btn-secondary">Voltar</a>
    <a href="{{ route('estacionamentos.edit', $estacionamento->id) }}" class="btn btn-warning">Editar</a>
</div>
@endsection
