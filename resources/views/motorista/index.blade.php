@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Motoristas Oficiais</h1>

    <a href="{{ route('motoristas.create') }}" class="btn btn-success mb-3">Cadastrar Novo Motorista</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($motoristas->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($motoristas as $motorista)
                    <tr>
                        <td>
                            @if($motorista->foto)
                                <img src="{{ asset('storage/' . $motorista->foto) }}" width="80" height="80" alt="Foto">
                            @else
                                <span>Sem foto</span>
                            @endif
                        </td>
                        <td>{{ $motorista->nome }}</td>
                        <td>{{ $motorista->matricula }}</td>
                        <td>
                            <a href="{{ route('motoristas.show', $motorista->id) }}" class="btn btn-info btn-sm">Visualizar</a>
                            <a href="{{ route('motoristas.edit', $motorista->id) }}" class="btn btn-warning btn-sm">Editar</a>

                            <form action="{{ route('motoristas.destroy', $motorista->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir este motorista?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Nenhum motorista cadastrado.</p>
    @endif
</div>
@endsection
