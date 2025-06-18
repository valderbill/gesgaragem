@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes da Permissão</h1>

    <div class="mb-3">
        <strong>ID:</strong> {{ $permissao->id }}
    </div>

    <div class="mb-3">
        <strong>Nome:</strong> {{ $permissao->nome }}
    </div>

    <div class="mb-3">
        <strong>Descrição:</strong> {{ $permissao->descricao }}
    </div>

    <a href="{{ route('permissoes.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
