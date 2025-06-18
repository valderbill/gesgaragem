@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Perfis</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('perfis.create') }}" class="btn btn-primary mb-3">Novo Perfil</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perfis as $perfil)
            <tr>
                <td>{{ $perfil->id }}</td>
                <td>{{ $perfil->nome }}</td>
                <td>
                    <a href="{{ route('perfis.show', $perfil) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('perfis.edit', $perfil) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('perfis.destroy', $perfil) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Deseja mesmo deletar?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Deletar</button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
