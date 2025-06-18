@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Estacionamento</h1>

    <form action="{{ route('estacionamentos.update', $estacionamento->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="localizacao" class="form-label">Localização</label>
            <input type="text" name="localizacao" id="localizacao" class="form-control" value="{{ old('localizacao', $estacionamento->localizacao) }}" required>
        </div>

        <div class="mb-3">
            <label for="vagas_particulares" class="form-label">Vagas Particulares</label>
            <input type="number" name="vagas_particulares" id="vagas_particulares" class="form-control" min="0" value="{{ old('vagas_particulares', $estacionamento->vagas_particulares) }}" required>
        </div>

        <div class="mb-3">
            <label for="vagas_oficiais" class="form-label">Vagas Oficiais</label>
            <input type="number" name="vagas_oficiais" id="vagas_oficiais" class="form-control" min="0" value="{{ old('vagas_oficiais', $estacionamento->vagas_oficiais) }}" required>
        </div>

        <div class="mb-3">
            <label for="vagas_motos" class="form-label">Vagas Motos</label>
            <input type="number" name="vagas_motos" id="vagas_motos" class="form-control" min="0" value="{{ old('vagas_motos', $estacionamento->vagas_motos) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('estacionamentos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
