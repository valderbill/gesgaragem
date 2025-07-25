@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Relatório de Acessos Liberados</h2>

    {{-- Botão de Imprimir com filtros aplicados --}}
    <a href="{{ route('relatorios.acessos_liberados.exportar', request()->query()) }}" target="_blank" class="btn btn-secondary mb-3">Imprimir PDF</a>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ request('nome') }}">
        </div>

        <div class="col-md-4">
            <label>Matricula</label>
            <input type="text" name="matricula" class="form-control" value="{{ request('matricula') }}">
        </div>

        <div class="col-md-4">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="t" {{ request('status') == 't' ? 'selected' : '' }}>Ativo</option>
                <option value="f" {{ request('status') == 'f' ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <hr>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Matrícula</th>
                <th>Status</th>
                <th>Data de Cadastro</th>
                <th>Criado Por</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($acessos as $acesso)
                <tr>
                    <td>{{ $acesso->nome }}</td>
                    <td>{{ $acesso->matricula }}</td>
                    <td>{{ $acesso->status == 't' ? 'Ativo' : 'Inativo' }}</td>
                    <td>{{ \Carbon\Carbon::parse($acesso->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ optional($acesso->criador)->nome ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhum acesso encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginação --}}
    <div class="d-flex justify-content-center">
        {{ $acessos->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
