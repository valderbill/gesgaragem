@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes da Ocorrência</h1>

    {{-- Dados da ocorrência inicial --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5><strong>Ocorrência Inicial:</strong></h5>
            <p>{{ $ocorrencia->ocorrencia }}</p>

            <p><strong>Data/Hora:</strong> {{ \Carbon\Carbon::parse($ocorrencia->horario)->format('d/m/Y H:i') }}</p>
            <p><strong>Registrado por:</strong> {{ optional($ocorrencia->usuario)->nome ?? 'Não informado' }}</p>
        </div>
    </div>

    {{-- Histórico de acompanhamentos --}}
    <div class="card">
        <div class="card-header">
            <h5>Acompanhamentos</h5>
        </div>
        <div class="card-body">
            @if($ocorrencia->acompanhamentos->count())
                <ul class="list-group">
                    @foreach($ocorrencia->acompanhamentos as $acompanhamento)
                        <li class="list-group-item">
                            <p>{{ $acompanhamento->descricao }}</p>
                            <small>
                                <strong>Data/Hora:</strong> {{ \Carbon\Carbon::parse($acompanhamento->horario)->format('d/m/Y H:i') }} |
                                <strong>Por:</strong> {{ optional($acompanhamento->usuario)->nome ?? 'Não informado' }}
                            </small>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Não há acompanhamentos registrados.</p>
            @endif
        </div>
    </div>

    {{-- Botões --}}
    <div class="mt-3">
        <a href="{{ route('acompanhamentos.create', $ocorrencia->id) }}" class="btn btn-primary">
            Adicionar Acompanhamento
        </a>
        <a href="{{ route('ocorrencias.index') }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>
</div>
@endsection