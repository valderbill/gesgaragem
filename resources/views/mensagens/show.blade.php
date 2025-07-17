@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>{{ $mensagem->titulo }}</h1>

    <p><strong>Remetente:</strong> {{ $mensagem->remetente->nome }}</p>
    <p><strong>Enviado em:</strong> {{ $mensagem->created_at->format('d/m/Y H:i') }}</p>

    <div class="mb-3">
        <strong>Conteúdo:</strong>
        <p>{{ $mensagem->conteudo }}</p>
    </div>

    <div class="mb-3">
        <strong>Destinatários:</strong><br>
        @foreach($mensagem->destinatarios as $dest)
            <span class="badge bg-secondary">{{ $dest->destinatario->nome }}</span>
        @endforeach
    </div>

    <a href="{{ route('mensagens.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
