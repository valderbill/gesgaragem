@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Mensagens</h1>

    <a href="{{ route('mensagens.create') }}" class="btn btn-success mb-3">Nova Mensagem</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h3>Enviadas</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Destinatários</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($enviadas as $mensagem)
                <tr>
                    <td>{{ $mensagem->titulo }}</td>
                    <td>
                        @foreach($mensagem->destinatarios as $dest)
                            <span class="badge bg-secondary">{{ $dest->destinatario->nome }}</span>
                        @endforeach
                    </td>
                    <td>{{ $mensagem->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('mensagens.show', $mensagem->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('mensagens.edit', $mensagem->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('mensagens.destroy', $mensagem->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Deseja excluir esta mensagem?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Nenhuma mensagem enviada.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3>Recebidas</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Remetente</th>
                <th>Título</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recebidas as $recebida)
                <tr>
                    <td>{{ $recebida->mensagem->remetente->nome }}</td>
                    <td>{{ $recebida->mensagem->titulo }}</td>
                    <td>
                        @if($recebida->lida)
                            ✅ Lida
                        @else
                            📬 Não lida
                        @endif
                    </td>
                    <td>{{ $recebida->mensagem->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('mensagens.show', $recebida->mensagem->id) }}" class="btn btn-info btn-sm">Ver</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Nenhuma mensagem recebida.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
