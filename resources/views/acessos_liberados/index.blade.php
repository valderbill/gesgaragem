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
                <th>Nome</th>
                <th>Matrícula</th>
                <th>Criado por</th>
                <th>Data de Criação</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($acessos as $acesso)
                <tr>
                    <td>{{ $acesso->nome }}</td>
                    <td>{{ $acesso->matricula }}</td>
                    <td>{{ $acesso->usuario->nome ?? 'Desconhecido' }}</td>
                    <td>{{ $acesso->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('acessos_liberados.status', $acesso->id) }}" method="POST" class="d-flex gap-2 align-items-center" onsubmit="return confirm('Tem certeza que deseja alterar o status?');">
                            @csrf
                            @method('PATCH')
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_{{ $acesso->id }}" value="1" {{ $acesso->status ? 'checked' : '' }} onchange="this.form.submit()" id="ativo_{{ $acesso->id }}">
                                <label class="form-check-label" for="ativo_{{ $acesso->id }}">Ativo</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_{{ $acesso->id }}" value="0" {{ !$acesso->status ? 'checked' : '' }} onchange="this.form.submit()" id="inativo_{{ $acesso->id }}">
                                <label class="form-check-label" for="inativo_{{ $acesso->id }}">Inativo</label>
                            </div>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('acessos_liberados.show', $acesso->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('acessos_liberados.edit', $acesso->id) }}" class="btn btn-warning btn-sm">Editar</a>
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
