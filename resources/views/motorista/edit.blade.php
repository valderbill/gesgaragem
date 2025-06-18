@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Motorista</h1>

    <form action="{{ route('motoristas.update', $motorista->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" value="{{ old('nome', $motorista->nome) }}" required>
        </div>

        <div class="mb-3">
            <label for="matricula" class="form-label">Matrícula:</label>
            <input type="text" class="form-control" name="matricula" id="matricula" value="{{ old('matricula', $motorista->matricula) }}" required>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto (opcional):</label>
            <input type="file" class="form-control" name="foto" id="foto">
            @if($motorista->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $motorista->foto) }}" alt="Foto do Motorista" width="150">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('motoristas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
