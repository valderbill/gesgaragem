@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Detalhes do Usuário</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $usuario->nome }}</h5>
            <p class="card-text"><strong>Matrícula:</strong> {{ $usuario->matricula }}</p>
            <p class="card-text"><strong>Perfil:</strong> {{ $usuario->perfil->nome ?? 'N/A' }}</p>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-3">Voltar</a>
        </div>
    </div>
</div>
@endsection
