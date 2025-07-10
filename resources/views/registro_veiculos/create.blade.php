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
                <select name="veiculo_id" id="placa" class="form-select" required></select>
            </div>

            <div class="col-md-2 mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" name="marca" id="marca" class="form-control" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <label for="modelo" class="form-label">Modelo</label>
                <input type="text" name="modelo" id="modelo" class="form-control" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <label for="cor" class="form-label">Cor</label>
                <input type="text" name="cor" id="cor" class="form-control" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <input type="text" id="tipo" class="form-control" readonly>
                <input type="hidden" name="tipo" id="tipo-hidden">
            </div>

            <div class="col-md-4 mb-3" id="motorista-entrada-area">
                <label class="form-label">Motorista Entrada</label>
                <select name="motorista_entrada_id" id="motorista_entrada_id" class="form-select"></select>
            </div>

            <div class="col-md-2 mb-3">
                <label for="quantidade_passageiros" class="form-label">Passageiros</label>
                <select name="quantidade_passageiros" id="quantidade_passageiros" class="form-select" required>
                    @for($i = 0; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
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

{{-- Scripts --}}
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
        const motoristaArea = $('#motorista-entrada-area');
        const motoristaSelect = $('#motorista_entrada_id');

        // Remove campos antigos
        $('#motorista_nome_display').remove();
        $('#motorista_entrada_id_hidden').remove();

        if (tipo === 'OFICIAL') {
            motoristaSelect.prop('disabled', false).show().val(null).trigger('change');
        } else {
            motoristaSelect.prop('disabled', true).hide().val(null).trigger('change');

            // Exibe nome do motorista readonly e input hidden com o id
            $('<input type="text" class="form-control mb-2" id="motorista_nome_display" readonly>')
                .val(dados.motorista_nome || 'Sem motorista')
                .appendTo(motoristaArea);

            $('<input type="hidden" name="motorista_entrada_id" id="motorista_entrada_id_hidden">')
                .val(dados.motorista_id)
                .appendTo(motoristaArea);
        }
    }

    $('#placa').select2({
        placeholder: 'Digite a placa...',
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("veiculos.buscar") }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ term: params.term }),
            processResults: data => ({
                results: data.results.map(item => ({
                    id: item.id,
                    text: item.placa,
                    marca: item.marca,
                    modelo: item.modelo,
                    cor: item.cor,
                    tipo: item.tipo,
                    motorista_id: item.motorista_id,
                    motorista_nome: item.motorista_nome
                }))
            }),
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
            data: params => ({
                term: params.term,
                tipo: $('#tipo-hidden').val()
            }),
            processResults: data => ({
                results: data.results.map(item => ({
                    id: item.id,
                    text: item.nome
                }))
            }),
            cache: true
        }
    });

    $('#placa').on('select2:select', function (e) {
        preencherCampos(e.params.data);
    });
});
</script>
@endsection