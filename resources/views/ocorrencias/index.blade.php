@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ocorrências</h1>

    {{-- Exibir nome do usuário logado --}}
    @if(Auth::check())
        <p><strong>Usuário logado:</strong> {{ Auth::user()->nome }}</p>
    @else
        <p><strong>Usuário não localizado</strong></p>
    @endif

    <a href="{{ route('ocorrencias.create') }}" class="btn btn-primary mb-3">Nova Ocorrência</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ocorrência</th>
                <th>Horário</th>
                <th>Usuário</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ocorrencias as $ocorrencia)
            <tr>
                <td>{{ Str::limit($ocorrencia->ocorrencia, 50) }}</td>
                <td>{{ \Carbon\Carbon::parse($ocorrencia->horario)->format('d/m/Y H:i:s') }}</td>
                <td>{{ $ocorrencia->usuario->nome ?? Auth::user()->nome }}</td>
                <td>
                    <a href="{{ route('ocorrencias.edit', $ocorrencia->id) }}" class="btn btn-success btn-sm">Incluir</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $ocorrencias->links() }}
</div>
@endsection
