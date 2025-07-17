@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Relatório de Motoristas Oficiais</h2>

    <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('relatorios.motoristas.exportar', request()->query()) }}" class="btn btn-secondary me-2">Imprimir PDF</a>
        <button type="submit" form="form-filtros" class="btn btn-primary me-2">Filtrar</button>
        <button id="limpar" class="btn btn-outline-secondary">Limpar</button>
    </div>

    <form method="GET" class="row g-3 mb-4" id="form-filtros">
        <div class="col-md-4">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ request('nome') }}">
        </div>

        <div class="col-md-4">
            <label>Matrícula</label>
            <input type="text" name="matricula" class="form-control" value="{{ request('matricula') }}">
        </div>

        <div class="col-md-4">
            <label>Status</label>
            <select name="ativo" class="form-select">
                <option value="">Todos</option>
                <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativo</option>
                <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th>Nome</th>
                <th>Matrícula</th>
                <th>Status</th>
                <th>Foto</th>
                <th>Cadastrado em</th>
            </tr>
        </thead>
        <tbody>
            @forelse($motoristas as $motorista)
                <tr class="text-center">
                    <td>{{ $motorista->nome }}</td>
                    <td>{{ $motorista->matricula }}</td>
                    <td>
                        <span class="badge bg-{{ $motorista->ativo ? 'success' : 'secondary' }}">
                            {{ $motorista->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td>
                        @if($motorista->foto)
                            <img src="{{ asset('storage/' . $motorista->foto) }}" alt="Foto" width="40">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($motorista->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhum motorista encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $motoristas->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('limpar').addEventListener('click', function () {
        const form = document.getElementById('form-filtros');
        form.querySelectorAll('input, select').forEach(el => {
            if (el.tagName === 'SELECT') {
                el.selectedIndex = 0;
            } else if (el.type !== 'submit' && el.type !== 'button') {
                el.value = '';
            }
        });
        form.submit();
    });
</script>
@endsection
