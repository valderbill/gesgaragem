@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Relatório de Registros de Veículos</h2>

    @php
        function gerarHorarios() {
            $horarios = [];
            for ($h = 0; $h < 24; $h++) {
                for ($m = 0; $m < 60; $m += 15) {
                    $horarios[] = sprintf('%02d:%02d', $h, $m);
                }
            }
            return $horarios;
        }
        $horarios = gerarHorarios();
    @endphp

    <form method="GET" class="row g-3 mb-3" id="form-filtros">
        <div class="col-md-3">
            <label>Placa</label>
            <input type="text" name="placa" class="form-control" value="{{ request('placa') }}">
        </div>

        <div class="col-md-3">
            <label>Tipo</label>
            <select name="tipo" class="form-select">
                <option value="">Todos</option>
                <option value="OFICIAL" {{ request('tipo') == 'OFICIAL' ? 'selected' : '' }}>OFICIAL</option>
                <option value="PARTICULAR" {{ request('tipo') == 'PARTICULAR' ? 'selected' : '' }}>PARTICULAR</option>
                <option value="MOTO" {{ request('tipo') == 'MOTO' ? 'selected' : '' }}>MOTO</option>
            </select>
        </div>

        <div class="col-md-3">
            <label>Localização</label>
            <select name="localizacao" class="form-select">
                <option value="">Todas</option>
                @foreach ($localizacoes as $loc)
                    <option value="{{ $loc->localizacao }}" {{ request('localizacao') == $loc->localizacao ? 'selected' : '' }}>
                        {{ $loc->localizacao }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Motorista Entrada</label>
            <select name="nome_motorista_entrada" class="form-select">
                <option value="">Todos</option>
                @foreach ($motoristasEntrada as $motorista)
                    <option value="{{ $motorista }}" {{ request('nome_motorista_entrada') == $motorista ? 'selected' : '' }}>
                        {{ $motorista }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Motorista Saída</label>
            <select name="nome_motorista_saida" class="form-select">
                <option value="">Todos</option>
                @foreach ($motoristasSaida as $motorista)
                    <option value="{{ $motorista }}" {{ request('nome_motorista_saida') == $motorista ? 'selected' : '' }}>
                        {{ $motorista }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Usuário Entrada</label>
            <select name="usuario_entrada" class="form-select">
                <option value="">Todos</option>
                @foreach ($usuariosEntrada as $usuario)
                    <option value="{{ $usuario->nome }}" {{ request('usuario_entrada') == $usuario->nome ? 'selected' : '' }}>
                        {{ $usuario->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Usuário Saída</label>
            <select name="usuario_saida" class="form-select">
                <option value="">Todos</option>
                @foreach ($usuariosSaida as $usuario)
                    <option value="{{ $usuario->nome }}" {{ request('usuario_saida') == $usuario->nome ? 'selected' : '' }}>
                        {{ $usuario->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Data Inicial (Entrada)</label>
            <input type="date" name="data_inicial" class="form-control" value="{{ request('data_inicial') }}">
        </div>

        <div class="col-md-3">
            <label>Hora Inicial (Entrada)</label>
            <select name="hora_inicial" class="form-select">
                <option value="">--</option>
                @foreach($horarios as $hora)
                    <option value="{{ $hora }}" {{ request('hora_inicial') == $hora ? 'selected' : '' }}>
                        {{ $hora }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Data Final (Saída)</label>
            <input type="date" name="data_final" class="form-control" value="{{ request('data_final') }}">
        </div>

        <div class="col-md-3">
            <label>Hora Final (Saída)</label>
            <select name="hora_final" class="form-select">
                <option value="">--</option>
                @foreach($horarios as $hora)
                    <option value="{{ $hora }}" {{ request('hora_final') == $hora ? 'selected' : '' }}>
                        {{ $hora }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Filtrar</button>

            <button type="button" class="btn btn-outline-danger" id="limpar-tudo">Limpar</button>

            <a href="{{ route('relatorios.registros.exportar', request()->query()) }}" target="_blank" class="btn btn-secondary">
                Imprimir PDF
            </a>
        </div>
    </form>

    {{-- Tabela --}}
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Cor</th>
                <th>Tipo</th>
                <th>Localização</th>
                <th>Motorista Entrada</th>
                <th>Motorista Saída</th>
                <th>Entrada</th>
                <th>Saída</th>
                <th>Usuário Entrada</th>
                <th>Usuário Saída</th>
                <th>Passageiros</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registros as $registro)
                <tr class="text-center">
                    <td>{{ $registro->placa }}</td>
                    <td>{{ $registro->marca }}</td>
                    <td>{{ $registro->modelo }}</td>
                    <td>{{ $registro->cor }}</td>
                    <td>{{ $registro->tipo }}</td>
                    <td>{{ optional($registro->estacionamento)->localizacao ?? '-' }}</td>
                    <td>{{ $registro->nome_motorista_entrada ?? '-' }}</td>
                    <td>{{ $registro->nome_motorista_saida ?? '-' }}</td>
                    <td>{{ $registro->horario_entrada_formatado }}</td>
                    <td>{{ $registro->horario_saida_formatado }}</td>
                    <td>{{ optional($registro->usuarioEntrada)->nome ?? '-' }}</td>
                    <td>{{ optional($registro->usuarioSaida)->nome ?? '-' }}</td>
                    <td>{{ $registro->quantidade_passageiros }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">Nenhum registro encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $registros->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Limpa todos os campos do formulário
    document.getElementById('limpar-tudo').addEventListener('click', function () {
        const form = document.getElementById('form-filtros');
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });
    });
</script>
@endsection
