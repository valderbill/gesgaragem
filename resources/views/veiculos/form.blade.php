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
    <label for="tipo" class="form-label">Tipo</label>
    <select name="tipo" id="tipo" class="form-select" required>
        <option value="">Selecione...</option>
        <option value="OFICIAL" {{ old('tipo') == 'OFICIAL' ? 'selected' : '' }}>OFICIAL</option>
        <option value="PARTICULAR" {{ old('tipo') == 'PARTICULAR' ? 'selected' : '' }}>PARTICULAR</option>
        <option value="MOTO" {{ old('tipo') == 'MOTO' ? 'selected' : '' }}>MOTO</option>
    </select>
</div>

<div class="mb-3">
    <label for="marca" class="form-label">Marca</label>
    <input type="text" name="marca" id="marca" class="form-control"
           value="{{ old('marca') }}" required>
</div>

{{-- Campo de motorista, exibido apenas se tipo for OFICIAL --}}
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
