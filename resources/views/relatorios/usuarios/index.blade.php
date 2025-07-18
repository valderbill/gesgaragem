@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Relatório de Usuários</h2>

    {{-- Botão de Imprimir com filtros aplicados --}}
    <div class="mb-3">
        <a href="{{ route('relatorios.usuarios.exportar', request()->query()) }}" target="_blank" class="btn btn-secondary">
            Imprimir PDF
        </a>
    </div>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ request('nome') }}">
        </div>

        <div class="col-md-3">
            <label>Matrícula</label>
            <input type="text" name="matricula" class="form-control" value="{{ request('matricula') }}">
        </div>

        <div class="col-md-3">
            <label>Status</label>
            <select name="ativo" class="form-select">
                <option value="">Todos</option>
                <option value="1" {{ request('ativo') == '1' ? 'selected' : '' }}>Ativo</option>
                <option value="0" {{ request('ativo') == '0' ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>

        <div class="col-md-3">
            <label>Perfil</label>
            <select name="perfil_id" class="form-select">
                <option value="">Todos</option>
                @foreach($perfis as $perfil)
                    <option value="{{ $perfil->id }}" {{ request('perfil_id') == $perfil->id ? 'selected' : '' }}>
                        {{ $perfil->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Data Inicial (Criação)</label>
            <input type="date" name="data_inicial" class="form-control" value="{{ request('data_inicial') }}">
        </div>

        <div class="col-md-3">
            <label>Data Final (Criação)</label>
            <input type="date" name="data_final" class="form-control" value="{{ request('data_final') }}">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Matrícula</th>
                <th>Perfil</th>
                <th>Status</th>
                <th>Criado por</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->nome }}</td>
                    <td>{{ $usuario->matricula }}</td>
                    <td>{{ optional($usuario->perfil)->nome ?? '-' }}</td>
                    <td>{{ $usuario->ativo ? 'Ativo' : 'Inativo' }}</td>
                    <td>{{ optional($usuario->criador)->nome ?? '-' }}</td>
                    <td>{{ optional($usuario->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhum usuário encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginação Bootstrap --}}
    <div class="d-flex justify-content-center">
        {{ $usuarios->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
