<div class="row">
    <div class="col-md-2 mb-3">
        <label for="placa" class="form-label">Placa</label>
        <input type="text" name="placa" id="placa" class="form-control" maxlength="7"
               value="{{ old('placa', $veiculo->placa ?? '') }}" required placeholder="Ex: ABC1234 ou ABC1D23">
    </div>

    <div class="col-md-2 mb-3">
        <label for="modelo" class="form-label">Modelo</label>
        <input type="text" name="modelo" id="modelo" class="form-control"
               value="{{ old('modelo', $veiculo->modelo ?? '') }}" required>
    </div>

    <div class="col-md-2 mb-3">
        <label for="cor" class="form-label">Cor</label>
        <input type="text" name="cor" id="cor" class="form-control"
               value="{{ old('cor', $veiculo->cor ?? '') }}" required>
    </div>

    <div class="col-md-3 mb-3">
        <label for="marca" class="form-label">Marca</label>
        <input type="text" name="marca" id="marca" class="form-control"
               value="{{ old('marca', $veiculo->marca ?? '') }}" required>
    </div>

    <div class="col-md-3 mb-3">
        <label for="tipo" class="form-label">Tipo</label>
        <select name="tipo" id="tipo" class="form-select" required>
            <option value="">Selecione...</option>
            <option value="OFICIAL" {{ old('tipo', $veiculo->tipo ?? '') == 'OFICIAL' ? 'selected' : '' }}>OFICIAL</option>
            <option value="PARTICULAR" {{ old('tipo', $veiculo->tipo ?? '') == 'PARTICULAR' ? 'selected' : '' }}>PARTICULAR</option>
            <option value="MOTO" {{ old('tipo', $veiculo->tipo ?? '') == 'MOTO' ? 'selected' : '' }}>MOTO</option>
        </select>
    </div>
</div>

<div class="row">
    {{-- Motorista Oficial (OFICIAL) --}}
    <div class="col-md-6 mb-3" id="motorista-oficial-container" style="display: none;">
        <label for="motorista_id" class="form-label">Motorista Oficial</label>
        <select name="motorista_id" id="motorista_id" class="form-select">
            <option value="">Selecione...</option>
            @foreach($motoristasOficiais as $motorista)
                <option value="{{ $motorista->id }}"
                    {{ old('motorista_id', $veiculo->motorista_id ?? '') == $motorista->id ? 'selected' : '' }}>
                    {{ $motorista->nome }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Nome do Motorista Visitante (PARTICULAR/MOTO) --}}
    <div class="col-md-12 mb-3" id="motorista-nome-container" style="display: none;">
        <label for="motorista_nome" class="form-label">Nome do Motorista</label>
        <select id="motorista_nome" class="form-select" style="width: 100%;">
            @if(old('motorista_nome'))
                <option value="{{ old('acesso_id') }}" selected>{{ old('motorista_nome') }}</option>
            @elseif(isset($veiculo) && $veiculo->acesso)
                <option value="{{ $veiculo->acesso_id }}" selected>{{ $veiculo->acesso->nome }}</option>
            @endif
        </select>
        <input type="hidden" name="acesso_id" id="acesso_id_hidden"
               value="{{ old('acesso_id', $veiculo->acesso_id ?? '') }}">
    </div>
</div>
