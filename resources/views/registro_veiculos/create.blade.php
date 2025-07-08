@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container">
    <h1 class="mb-4">Criar Novo Registro de Veículo</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('registro_veiculos.store') }}" method="POST">
        @csrf

        <div class="row">
            @php
                $estacionamentoSelecionado = $estacionamentos->firstWhere('id', session('estacionamento_id'));
            @endphp

            <div class="col-md-4 mb-3">
                <label class="form-label">Estacionamento</label>
                <input type="text" class="form-control" value="{{ $estacionamentoSelecionado->localizacao ?? 'Não definido' }}" readonly>
                <input type="hidden" name="estacionamento_id" value="{{ $estacionamentoSelecionado->id ?? '' }}">
            </div>

            <div class="col-md-2 mb-3">
                <label for="placa" class="form-label">Placa</label>
                <select name="veiculo_id" id="placa" class="form-select" required>
                    @if(old('veiculo_id') && old('placa'))
                        <option value="{{ old('veiculo_id') }}" selected>{{ old('placa') }}</option>
                    @endif
                </select>
            </div>

            <div class="col-md-2 mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" name="marca" id="marca" class="form-control" value="{{ old('marca') }}" required maxlength="50" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <label for="modelo" class="form-label">Modelo</label>
                <input type="text" name="modelo" id="modelo" class="form-control" value="{{ old('modelo') }}" required maxlength="50" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <label for="cor" class="form-label">Cor</label>
                <input type="text" name="cor" id="cor" class="form-control" value="{{ old('cor') }}" required maxlength="20" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" class="form-select" disabled>
                    <option value="">Tipo</option>
                    <option value="OFICIAL" {{ old('tipo') == 'OFICIAL' ? 'selected' : '' }}>Oficial</option>
                    <option value="PARTICULAR" {{ old('tipo') == 'PARTICULAR' ? 'selected' : '' }}>Particular</option>
                    <option value="MOTO" {{ old('tipo') == 'MOTO' ? 'selected' : '' }}>Moto</option>
                </select>
                <input type="hidden" name="tipo" id="tipo-hidden" value="{{ old('tipo') }}">
            </div>

            <div class="col-md-3 mb-3">
                <label for="motorista_entrada_id" class="form-label">Motorista Entrada</label>
                <select name="motorista_entrada_id" id="motorista_entrada_id" class="form-select" required>
                    <option value="">Digite o nome do motorista...</option>
                </select>
            </div>

            <div class="col-md-2 mb-3">
                <label for="quantidade_passageiros" class="form-label">Passageiros</label>
                <select name="quantidade_passageiros" id="quantidade_passageiros" class="form-select" required>
                    @for($i = 0; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('quantidade_passageiros', 0) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">Registrar Entrada</button>
            <a href="{{ route('registro_veiculos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    function preencherCampos(dados) {
        $('#marca').val(dados.marca || '');
        $('#modelo').val(dados.modelo || '');
        $('#cor').val(dados.cor || '');
        $('#tipo').val(dados.tipo || '');
        $('#tipo-hidden').val(dados.tipo || '');

        const tipo = dados.tipo || '';
        const motoristaSelect = $('#motorista_entrada_id');

        // Sempre habilite o campo para garantir que será enviado no POST
        motoristaSelect.prop('disabled', false);
        motoristaSelect.empty();

        if (tipo === 'OFICIAL') {
            motoristaSelect.val(null).trigger('change');
        } else {
            if (dados.motorista_entrada_id && dados.motorista_nome) {
                const option = new Option(dados.motorista_nome, dados.motorista_entrada_id, true, true);
                motoristaSelect.append(option).trigger('change');
            }
        }
    }

    $('#placa').select2({
        placeholder: 'Digite a placa...',
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("veiculos.buscar") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { term: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.results.map(item => ({
                        id: item.id,
                        text: item.placa,
                        placa: item.placa,
                        marca: item.marca,
                        modelo: item.modelo,
                        cor: item.cor,
                        tipo: item.tipo,
                        motorista_entrada_id: item.motorista_entrada_id,
                        motorista_nome: item.motorista_nome
                    }))
                };
            },
            cache: true
        }
    });

    $('#motorista_entrada_id').select2({
        placeholder: 'Digite o nome do motorista...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("registro_veiculos.buscar_motoristas_acesso") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term,
                    tipo: 'OFICIAL'
                };
            },
            processResults: function (data) {
                return {
                    results: data.results.map(item => ({
                        id: item.id,
                        text: item.nome
                    }))
                };
            },
            cache: true
        }
    });

    $('#placa').on('select2:select', function (e) {
        const dados = e.params.data;
        preencherCampos(dados);
    });

    @if(old('veiculo_id'))
        $.ajax({
            url: '{{ route("veiculos.buscar") }}',
            data: { id: '{{ old("veiculo_id") }}' },
            success: function(response) {
                if (response.results && response.results.length > 0) {
                    const dados = response.results[0];
                    const newOption = new Option(dados.placa, dados.id, true, true);
                    $('#placa').append(newOption).trigger('change');
                    preencherCampos(dados);
                }
            }
        });
    @endif
});
</script>
@endsection