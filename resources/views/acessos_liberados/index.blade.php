@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Acessos Liberados</h1>
    <a href="{{ route('acessos_liberados.create') }}" class="btn btn-primary mb-3">Novo Acesso</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Matrícula</th>               
                <th>Criado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($acessos as $acesso)
                <tr>
                    <td>{{ $acesso->id }}</td>
                    <td>{{ $acesso->nome }}</td>
                    <td>{{ $acesso->matricula }}</td>                  
                    <td>{{ $acesso->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('acessos_liberados.show', $acesso->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('acessos_liberados.edit', $acesso->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('acessos_liberados.destroy', $acesso->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Tem certeza?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Nenhum acesso liberado encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $acessos->links() }}
</div>
@endsection
