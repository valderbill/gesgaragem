@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes do Acesso Liberado</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $acesso->nome }}</h5>
            <p class="card-text"><strong>Matrícula:</strong> {{ $acesso->matricula }}</p>           
            <p class="card-text"><strong>Criado em:</strong> {{ $acesso->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <a href="{{ route('acessos_liberados.index') }}" class="btn btn-secondary mt-3">Voltar</a>
    <a href="{{ route('acessos_liberados.edit', $acesso->id) }}" class="btn btn-warning mt-3">Editar</a>
</div>
@endsection
