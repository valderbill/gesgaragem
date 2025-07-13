@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Relatório de Veículos</h2>

    <a href="{{ route('relatorios.veiculos.create') }}" class="btn btn-success mb-3">Novo Relatório</a>

    {{-- Botão de Imprimir com filtros aplicados --}}
    <a href="{{ route('relatorios.veiculos.exportar', request()->query()) }}" target="_blank" class="btn btn-secondary mb-3 ms-2">Imprimir PDF</a>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-2">
            <label>Placa</label>
            <input type="text" name="placa" class="form-control" value="{{ request('placa') }}">
        </div>

        <div class="col-md-2">
            <label>Modelo</label>
            <input type="text" name="modelo" class="form-control" value="{{ request('modelo') }}">
        </div>

        <div class="col-md-2">
            <label>Marca</label>
            <input type="text" name="marca" class="form-control" value="{{ request('marca') }}">
        </div>

        <div class="col-md-2">
            <label>Cor</label>
            <input type="text" name="cor" class="form-control" value="{{ request('cor') }}">
        </div>

        <div class="col-md-4">
            <label>Tipo de Veículo</label>
            <select name="tipos[]" class="form-select" multiple>
                <option value="OFICIAL" {{ collect(request('tipos'))->contains('OFICIAL') ? 'selected' : '' }}>Oficial</option>
                <option value="PARTICULAR" {{ collect(request('tipos'))->contains('PARTICULAR') ? 'selected' : '' }}>Particular</option>
                <option value="MOTO" {{ collect(request('tipos'))->contains('MOTO') ? 'selected' : '' }}>Moto</option>
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

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <hr>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Cor</th>
                <th>Tipo</th>
                <th>Motorista</th>
                <th>Data de Cadastro</th>
                <th>Criado Por</th> {{-- NOVO --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($veiculos as $veiculo)
                <tr>
                    <td>{{ $veiculo->placa }}</td>
                    <td>{{ $veiculo->modelo }}</td>
                    <td>{{ $veiculo->marca }}</td>
                    <td>{{ $veiculo->cor }}</td>
                    <td>{{ $veiculo->tipo }}</td>
                    <td>
                        @if ($veiculo->tipo === 'OFICIAL')
                            {{ optional($veiculo->motoristaOficial)->nome }}
                        @elseif (in_array($veiculo->tipo, ['PARTICULAR', 'MOTO']))
                            {{ optional($veiculo->acesso)->nome }}
                        @else
                            <em>Não identificado</em>
                        @endif
                    </td>
                    <td>
                        {{ $veiculo->criado_em ? \Carbon\Carbon::parse($veiculo->criado_em)->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td>
                        {{ optional($veiculo->criador)->nome ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Nenhum veículo encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $veiculos->appends(request()->query())->links() }}
    </div>
</div>
@endsection
