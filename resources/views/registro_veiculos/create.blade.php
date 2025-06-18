@extends('layouts.app')

@section('content')
<!-- Importa CSS do Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container">
    <h1>Criar Novo Registro de Veículo</h1>

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
            <!-- Campo Estacionamento (fixo, vindo da sessão) -->
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
                    @if(old('placa'))
                        <option value="{{ old('veiculo_id') }}" selected>{{ old('placa') }}</option>
                    @endif
                </select>
            </div>

            <div class="col-md-2 mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" name="marca" id="marca" class="form-control" value="{{ old('marca') }}" required maxlength="50" autocomplete="off" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <label for="modelo" class="form-label">Modelo</label>
                <input type="text" name="modelo" id="modelo" class="form-control" value="{{ old('modelo') }}" required maxlength="50" autocomplete="off" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <label for="cor" class="form-label">Cor</label>
                <input type="text" name="cor" id="cor" class="form-control" value="{{ old('cor') }}" required maxlength="20" autocomplete="off" readonly>
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
                    <option value="">Selecione motorista</option>
                    @foreach($motoristas as $motorista)
                        <option value="{{ $motorista->id }}" {{ old('motorista_entrada_id') == $motorista->id ? 'selected' : '' }}>
                            {{ $motorista->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">Registrar Entrada</button>
            <a href="{{ route('registro_veiculos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<!-- Scripts do jQuery e Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    function preencherCampos(dados) {
        $('#marca').val(dados.marca || '');
        $('#modelo').val(dados.modelo || '');
        $('#cor').val(dados.cor || '');
        $('#tipo').val(dados.tipo || '');
        $('#tipo-hidden').val(dados.tipo || '');
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
                        id: item.id ?? item.text ?? item.placa,
                        text: item.text ?? item.placa,
                        marca: item.marca,
                        modelo: item.modelo,
                        cor: item.cor,
                        tipo: item.tipo
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

    @if(old('placa'))
        var placaSelecionada = $('#placa').select2('data')[0];
        if (!placaSelecionada) {
            $.ajax({
                url: '{{ route("veiculos.buscar") }}',
                data: { term: '{{ old("placa") }}' },
                success: function(response) {
                    if (response.results && response.results.length > 0) {
                        preencherCampos(response.results[0]);
                    }
                }
            });
        } else {
            preencherCampos(placaSelecionada);
        }
    @endif
});
</script>
@endsection
