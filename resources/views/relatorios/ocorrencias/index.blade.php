@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Relatório de Ocorrências</h2>

    <div class="d-flex justify-content-end mb-2">
        <button type="submit" form="form-exportar-multiplas" class="btn btn-danger">Imprimir PDF</button>
        <button type="submit" form="form-filtros" class="btn btn-primary ms-2">Filtrar</button>
        <button id="limpar" class="btn btn-outline-secondary ms-2">Limpar</button>
    </div>

    <form method="GET" class="row g-3 mb-4" id="form-filtros">
        <div class="col-md-4">
            <label>Texto da Ocorrência</label>
            <input type="text" name="texto" class="form-control" value="{{ request('texto') }}">
        </div>

        <div class="col-md-3">
            <label>Usuário</label>
            <select name="usuario" class="form-select">
                <option value="">Todos</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->nome }}" {{ request('usuario') == $usuario->nome ? 'selected' : '' }}>
                        {{ $usuario->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Possui Acompanhamentos?</label>
            <select name="possui_acompanhamento" class="form-select">
                <option value="">Todos</option>
                <option value="sim" {{ request('possui_acompanhamento') == 'sim' ? 'selected' : '' }}>Sim</option>
                <option value="nao" {{ request('possui_acompanhamento') == 'nao' ? 'selected' : '' }}>Não</option>
            </select>
        </div>

        <div class="col-md-3">
            <label>Data Inicial</label>
            <input type="date" name="data_inicial" class="form-control" value="{{ request('data_inicial') }}">
        </div>

        <div class="col-md-3">
            <label>Data Final</label>
            <input type="date" name="data_final" class="form-control" value="{{ request('data_final') }}">
        </div>
    </form>

    <form method="POST" action="{{ route('relatorios.ocorrencias.exportar_selecionadas') }}" id="form-exportar-multiplas">
        @csrf
        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th><input type="checkbox" id="check-todos"></th>
                    <th>Data/Hora</th>
                    <th>Ocorrência</th>
                    <th>Usuário</th>
                    <th>Acompanhamentos</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ocorrencias as $ocorrencia)
                    <tr class="text-center">
                        <td>
                            <input type="checkbox" name="selecionadas[]" value="{{ $ocorrencia->id }}">
                        </td>
                        <td>{{ \Carbon\Carbon::parse($ocorrencia->horario)->format('d/m/Y H:i') }}</td>
                        <td class="text-start">
                            <div class="fw-bold text-dark">
                                • {{ \Carbon\Carbon::parse($ocorrencia->horario)->format('d/m/Y H:i') }} – {{ Str::limit($ocorrencia->ocorrencia, 100) }}
                            </div>
                            @if($ocorrencia->acompanhamentos->count())
                                <ul class="mt-1 mb-0 ps-3 small text-muted">
                                    @foreach($ocorrencia->acompanhamentos as $acompanhamento)
                                        <li>
                                            {{ \Carbon\Carbon::parse($acompanhamento->horario)->format('d/m/Y H:i') }} – {{ Str::limit($acompanhamento->descricao, 100) }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td>{{ $ocorrencia->usuario->nome ?? '-' }}</td>
                        <td>{{ $ocorrencia->acompanhamentos->count() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Nenhuma ocorrência encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </form>

    <div class="d-flex justify-content-center">
        {{ $ocorrencias->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkTodos = document.getElementById('check-todos');
        const checkboxes = document.querySelectorAll('input[name="selecionadas[]"]');

        if (checkTodos) {
            checkTodos.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = this.checked);
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    if (!this.checked) {
                        checkTodos.checked = false;
                    } else {
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        checkTodos.checked = allChecked;
                    }
                });
            });
        }

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
    });
</script>
@endsection
