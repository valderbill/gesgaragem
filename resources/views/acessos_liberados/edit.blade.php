@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Acesso Liberado</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('acessos_liberados.update', $acesso->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome', $acesso->nome) }}" required>
        </div>
        <div class="mb-3">
            <label for="matricula" class="form-label">Matrícula</label>
            <input type="text" name="matricula" id="matricula" class="form-control" value="{{ old('matricula', $acesso->matricula) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('acessos_liberados.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
