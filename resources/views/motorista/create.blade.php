@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Registrar Entrada de Veículo</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('registro_veiculos.store') }}">
        @csrf

        <div class="mb-3">
            <label for="veiculo_id" class="form-label">Placa</label>
            <select id="veiculo_id" name="veiculo_id" class="form-control" required></select>
        </div>

        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" id="marca" name="marca" class="form-control" required readonly>
        </div>

        <div class="mb-3">
            <label for="modelo" class="form-label">Modelo</label>
            <input type="text" id="modelo" name="modelo" class="form-control" required readonly>
        </div>

        <div class="mb-3">
            <label for="cor" class="form-label">Cor</label>
            <input type="text" id="cor" name="cor" class="form-control" required readonly>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <input type="text" id="tipo" name="tipo" class="form-control" required readonly>
        </div>

        <div class="mb-3" id="motorista-oficial-div" style="display: none;">
            <label for="motorista_entrada_id" class="form-label">Motorista Oficial</label>
            <select id="motorista_entrada_id" name="motorista_entrada_id" class="form-control">
                <option value="">Selecione um motorista</option>
                @foreach ($motoristas as $motorista)
                    <option value="{{ $motorista->id }}">{{ $motorista->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantidade_passageiros" class="form-label">Quantidade de Passageiros</label>
            <input type="number" name="quantidade_passageiros" id="quantidade_passageiros" class="form-control" required min="0" max="10">
        </div>

        <input type="hidden" name="estacionamento_id" value="{{ auth()->user()->estacionamento_id }}">

        <button type="submit" class="btn btn-success">Registrar Entrada</button>
        <a href="{{ route('registro_veiculos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
<!-- Select2 CSS e JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    $('#veiculo_id').select2({
        placeholder: 'Digite a placa...',
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("veiculos.buscar") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { term: params.term };
            },
            processResults: function (data) {
                return {
                    results: data // [{id, text}]
                };
            },
            cache: true
        }
    });

    $('#veiculo_id').on('select2:select', function (e) {
        const veiculoId = e.params.data.id;

        fetch(`/veiculos/buscar-por-id/${veiculoId}`)
            .then(response => response.json())
            .then(data => {
                $('#marca').val(data.marca);
                $('#modelo').val(data.modelo);
                $('#cor').val(data.cor);
                $('#tipo').val(data.tipo);

                if (data.tipo === 'OFICIAL') {
                    $('#motorista-oficial-div').show();
                } else {
                    $('#motorista-oficial-div').hide();
                    const input = $('<input>', {
                        type: 'hidden',
                        name: 'motorista_entrada_id',
                        value: data.motorista_id ?? ''
                    });
                    $('form').append(input);
                }
            })
            .catch(error => {
                console.error('Erro ao buscar veículo:', error);
            });
    });
});
</script>
@endsection
