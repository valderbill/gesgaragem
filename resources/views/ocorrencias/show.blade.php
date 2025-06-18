@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ocorrências</h1>
    <a href="{{ route('ocorrencias.create') }}" class="btn btn-primary mb-3">Nova Ocorrência</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Placa</th>
                <th>Ocorrência</th>
                <th>Horário</th>
                <th>Usuário</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ocorrencias as $ocorrencia)
            <tr>
                <td>{{ $ocorrencia->id }}</td>
                <td>{{ $ocorrencia->placa }}</td>
                <td>{{ Str::limit($ocorrencia->ocorrencia, 50) }}</td>
                <td>{{ $ocorrencia->horario }}</td>
                <td>{{ $ocorrencia->usuario->nome ?? '' }}</td>
                <td>
                    <a href="{{ route('ocorrencias.show', $ocorrencia->id) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('ocorrencias.edit', $ocorrencia->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('ocorrencias.destroy', $ocorrencia->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Confirmar exclusão?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $ocorrencias->links() }}
</div>
@endsection
