@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Editar Mensagem</h1>

    <form action="{{ route('mensagens.update', $mensagem->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="{{ $mensagem->titulo }}" required>
        </div>

        <div class="mb-3">
            <label for="conteudo" class="form-label">Conteúdo</label>
            <textarea name="conteudo" class="form-control" rows="5" required>{{ $mensagem->conteudo }}</textarea>
        </div>

        {{-- Opcional: lista de destinatários não editável para referência --}}
        <div class="mb-3">
            <label class="form-label">Destinatários</label><br>
            @foreach($mensagem->destinatarios as $dest)
                <span class="badge bg-secondary">{{ $dest->destinatario->nome }}</span>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="{{ route('mensagens.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
