@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes da Ocorrência</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $ocorrencia->id }}</p>
            <p><strong>Placa:</strong> {{ $ocorrencia->placa }}</p>
            <p><strong>Ocorrência:</strong> {{ $ocorrencia->ocorrencia }}</p>
            <p><strong>Data/Hora:</strong> {{ \Carbon\Carbon::parse($ocorrencia->horario)->format('d/m/Y H:i') }}</p>               
            <p><strong>Usuário:</strong> {{ optional($ocorrencia->usuario)->name ?? 'Não informado' }}</p>
        </div>
    </div>

    <a href="{{ route('acompanhamentos.create', $ocorrencia->id) }}" class="btn btn-success">Adicionar Acompanhamento</a>
    <a href="{{ route('ocorrencias.index') }}" class="btn btn-secondary">Voltar</a>

    <hr>

    <h3>Acompanhamentos</h3>

    @if($ocorrencia->acompanhamentos->isEmpty())
        <p>Nenhum acompanhamento registrado para esta ocorrência.</p>
    @else
        <ul class="list-group">
            @foreach($ocorrencia->acompanhamentos as $acompanhamento)
                <li class="list-group-item">
                    <strong>{{ \Carbon\Carbon::parse($acompanhamento->created_at)->format('d/m/Y H:i') }}</strong> - 
                    {{ $acompanhamento->descricao }}
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
