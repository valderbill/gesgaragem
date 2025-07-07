<div class="mb-3">
    <label for="placa" class="form-label">Placa</label>
    <input type="text" name="placa" id="placa" class="form-control" maxlength="7"
           value="{{ old('placa') }}" required placeholder="Ex: ABC1234 ou ABC1D23">
</div>

<div class="mb-3">
    <label for="modelo" class="form-label">Modelo</label>
    <input type="text" name="modelo" id="modelo" class="form-control"
           value="{{ old('modelo') }}" required>
</div>

<div class="mb-3">
    <label for="cor" class="form-label">Cor</label>
    <input type="text" name="cor" id="cor" class="form-control"
           value="{{ old('cor') }}" required>
</div>

<div class="mb-3">
    <label for="marca" class="form-label">Marca</label>
    <input type="text" name="marca" id="marca" class="form-control"
           value="{{ old('marca') }}" required>
</div>

<div class="mb-3">
    <label for="tipo" class="form-label">Tipo</label>
    <select name="tipo" id="tipo" class="form-select" required>
        <option value="">Selecione...</option>
        <option value="OFICIAL" {{ old('tipo') == 'OFICIAL' ? 'selected' : '' }}>OFICIAL</option>
        <option value="PARTICULAR" {{ old('tipo') == 'PARTICULAR' ? 'selected' : '' }}>PARTICULAR</option>
        <option value="MOTO" {{ old('tipo') == 'MOTO' ? 'selected' : '' }}>MOTO</option>
    </select>
</div>

{{-- Campo para OFICIAL: Select de acesso liberado --}}
<div class="mb-3" id="acesso-container" style="display: none;">
    <label for="acesso_id" class="form-label">Acesso Liberado</label>
    <select name="acesso_id" id="acesso_id" class="form-select">
        <option value="">Selecione...</option>
        @foreach($acessos as $acesso)
            <option value="{{ $acesso->id }}" {{ old('acesso_id') == $acesso->id ? 'selected' : '' }}>
                {{ $acesso->nome }} - {{ $acesso->matricula }}
            </option>
        @endforeach
    </select>
</div>

{{-- Campo para PARTICULAR/MOTO: buscar nome e preencher acesso_id --}}
<div class="mb-3" id="motorista-nome-container" style="display: none;">
    <label for="motorista_nome" class="form-label">Nome do Motorista</label>
    <input type="text" id="motorista_nome" class="form-control" placeholder="Digite para buscar...">
    <input type="hidden" name="acesso_id" id="acesso_id_hidden" value="{{ old('acesso_id') }}">
</div>

{{-- jQuery + Select2 --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoSelect = document.getElementById('tipo');
    const acessoSelectContainer = document.getElementById('acesso-container');
    const motoristaNomeContainer = document.getElementById('motorista-nome-container');
    const acessoSelect = document.getElementById('acesso_id');
    const motoristaInput = document.getElementById('motorista_nome');
    const acessoHidden = document.getElementById('acesso_id_hidden');

    function toggleCamposMotorista() {
        const tipo = tipoSelect.value;

        if (tipo === 'OFICIAL') {
            acessoSelectContainer.style.display = 'block';
            motoristaNomeContainer.style.display = 'none';
            motoristaInput.value = '';
            acessoHidden.value = '';
        } else if (tipo === 'PARTICULAR' || tipo === 'MOTO') {
            acessoSelectContainer.style.display = 'none';
            motoristaNomeContainer.style.display = 'block';
            acessoSelect.value = '';
        } else {
            acessoSelectContainer.style.display = 'none';
            motoristaNomeContainer.style.display = 'none';
            acessoSelect.value = '';
            motoristaInput.value = '';
            acessoHidden.value = '';
        }
    }

    tipoSelect.addEventListener('change', toggleCamposMotorista);
    toggleCamposMotorista();

    // Autocomplete do nome do motorista
    $('#motorista_nome').select2({
        placeholder: 'Digite o nome...',
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("acessos.buscar") }}', // Ajuste esta rota
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.nome + ' - ' + item.matricula
                    }))
                };
            },
            cache: true
        }
    });

    $('#motorista_nome').on('select2:select', function (e) {
        const selected = e.params.data;
        acessoHidden.value = selected.id;
    });
});
</script>
