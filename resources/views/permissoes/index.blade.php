@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Permissões</h1>
    <a href="{{ route('permissoes.create') }}" class="btn btn-primary mb-3">Nova Permissão</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissoes as $permissao)
            <tr>
                <td>{{ $permissao->id }}</td>
                <td>{{ $permissao->nome }}</td>
                <td>{{ $permissao->descricao }}</td>
                <td>
                    <a href="{{ route('permissoes.show', $permissao) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('permissoes.edit', $permissao) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('permissoes.destroy', $permissao) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Deseja realmente excluir?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $permissoes->links() }}
</div>
@endsection
