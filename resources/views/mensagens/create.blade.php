@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Nova Mensagem</h1>

    <form action="{{ route('mensagens.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="conteudo" class="form-label">Conteúdo</label>
            <textarea name="conteudo" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Destinatários</label>
            <select name="destinatarios[]" class="form-select" multiple required>
                <option value="todos">Enviar para Todos</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->nome }}</option>
                @endforeach
            </select>
            <small class="text-muted">Segure Ctrl (ou Cmd) para selecionar múltiplos.</small>
        </div>

        <button type="submit" class="btn btn-primary">Enviar</button>
        <a href="{{ route('mensagens.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
