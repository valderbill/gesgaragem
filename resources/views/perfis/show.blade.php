@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes do Perfil</h1>

    <p><strong>ID:</strong> {{ $perfil->id }}</p>
    <p><strong>Nome:</strong> {{ $perfil->nome }}</p>

    <a href="{{ route('perfis.index') }}" class="btn btn-secondary">Voltar</a>
    <a href="{{ route('perfis.edit', $perfil) }}" class="btn btn-warning">Editar</a>
</div>
@endsection
