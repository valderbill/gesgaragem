@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes do Motorista</h1>

    <div class="mb-3">
        <strong>Nome:</strong> {{ $motorista->nome }}
    </div>

    <div class="mb-3">
        <strong>Matrícula:</strong> {{ $motorista->matricula }}
    </div>

    <div class="mb-3">
        <strong>Foto:</strong><br>
        @if($motorista->foto)
            <img src="{{ asset('storage/' . $motorista->foto) }}" alt="Foto do Motorista" width="200">
        @else
            <p>Sem foto cadastrada.</p>
        @endif
    </div>

    <a href="{{ route('motoristas.edit', $motorista->id) }}" class="btn btn-warning">Editar</a>
    <a href="{{ route('motoristas.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
