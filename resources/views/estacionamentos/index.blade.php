@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista de Estacionamentos</h1>
    <a href="{{ route('estacionamentos.create') }}" class="btn btn-success mb-3">Novo Estacionamento</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Localização</th>
                <th>Vagas Particulares</th>
                <th>Vagas Oficiais</th>
                <th>Vagas Motos</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estacionamentos as $estacionamento)
            <tr>
                <td>{{ $estacionamento->id }}</td>
                <td>{{ $estacionamento->localizacao ?? 'Sem localização' }}</td>
                <td>{{ $estacionamento->vagas_particulares ?? 0 }}</td>
                <td>{{ $estacionamento->vagas_oficiais ?? 0 }}</td>
                <td>{{ $estacionamento->vagas_motos ?? 0 }}</td>
                <td>
                    <a href="{{ route('estacionamentos.show', $estacionamento->id) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('estacionamentos.edit', $estacionamento->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('estacionamentos.destroy', $estacionamento->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
