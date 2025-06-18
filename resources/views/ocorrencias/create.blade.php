@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Criar Ocorrência</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('ocorrencias.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="placa" class="form-label">Veículo (Placa)</label>
            <select name="placa" id="placa" class="form-control" required>
                <option value="">Selecione a placa</option>
                @foreach($veiculos as $veiculo)
                    <option value="{{ $veiculo->placa }}" {{ old('placa') == $veiculo->placa ? 'selected' : '' }}>
                        {{ $veiculo->placa }} - {{ $veiculo->modelo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="ocorrencia" class="form-label">Ocorrência</label>
            <textarea name="ocorrencia" id="ocorrencia" class="form-control" rows="4" required>{{ old('ocorrencia') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="horario" class="form-label">Horário</label>
            <input type="datetime-local" name="horario" id="horario" class="form-control" value="{{ old('horario') }}" required>
        </div>

        <div class="mb-3">
            <label for="usuario_id" class="form-label">Usuário</label>
            <select name="usuario_id" id="usuario_id" class="form-control" required>
                <option value="">Selecione o usuário</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                        {{ $usuario->nome }} - {{ $usuario->matricula }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('ocorrencias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
