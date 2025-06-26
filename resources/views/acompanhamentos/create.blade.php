@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Adicionar Acompanhamento</h1>

    {{-- Mostrar dados da ocorrência --}}
    <div class="card mb-3">
        <div class="card-body">
            <strong>Ocorrência:</strong> {{ $ocorrencia->ocorrencia }} <br>
            <strong>Data/Hora da Ocorrência:</strong> {{ \Carbon\Carbon::parse($ocorrencia->horario)->format('d/m/Y H:i') }} <br>
            <strong>Registrado por:</strong> {{ $ocorrencia->usuario->name ?? 'Não informado' }}
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('acompanhamentos.store', $ocorrencia->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição do Acompanhamento</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="4" required>{{ old('descricao') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="horario" class="form-label">Data/Hora do Acompanhamento</label>
            <input type="datetime-local" name="horario" id="horario" class="form-control" 
                   value="{{ old('horario', now()->format('Y-m-d\TH:i')) }}" required>
        </div>

        <button type="submit" class="btn btn-success">Salvar Acompanhamento</button>
        <a href="{{ route('ocorrencias.show', $ocorrencia->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
