<div class="row">
    <div class="col-md-2 mb-3">
        <label for="placa" class="form-label">Placa</label>
        <input type="text" name="placa" id="placa" class="form-control" maxlength="7"
               value="{{ old('placa') }}" required placeholder="Ex: ABC1234 ou ABC1D23">
    </div>
    <div class="col-md-2 mb-3">
        <label for="modelo" class="form-label">Modelo</label>
        <input type="text" name="modelo" id="modelo" class="form-control"
               value="{{ old('modelo') }}" required>
    </div>
    <div class="col-md-2 mb-3">
        <label for="cor" class="form-label">Cor</label>
        <input type="text" name="cor" id="cor" class="form-control"
               value="{{ old('cor') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label for="marca" class="form-label">Marca</label>
        <input type="text" name="marca" id="marca" class="form-control"
               value="{{ old('marca') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label for="tipo" class="form-label">Tipo</label>
        <select name="tipo" id="tipo" class="form-select" required>
            <option value="">Selecione...</option>
            <option value="OFICIAL" {{ old('tipo') == 'OFICIAL' ? 'selected' : '' }}>OFICIAL</option>
            <option value="PARTICULAR" {{ old('tipo') == 'PARTICULAR' ? 'selected' : '' }}>PARTICULAR</option>
            <option value="MOTO" {{ old('tipo') == 'MOTO' ? 'selected' : '' }}>MOTO</option>
        </select>
    </div>
</div>

<div class="row">
    {{-- Campo para OFICIAL: Select de motorista oficial --}}
    <div class="col-md-6 mb-3" id="motorista-oficial-container" style="display: none;">
        <label for="motorista_id" class="form-label">Motorista Oficial</label>
        <select name="motorista_id" id="motorista_id" class="form-select">
            <option value="">Selecione...</option>
            @foreach($motoristasOficiais as $motorista)
                <option value="{{ $motorista->id }}" {{ old('motorista_id') == $motorista->id ? 'selected' : '' }}>
                    {{ $motorista->nome }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Campo para PARTICULAR/MOTO: buscar nome e preencher acesso_id --}}
    <div class="col-md-12 mb-3" id="motorista-nome-container" style="display: none;">
        <label for="motorista_nome" class="form-label">Nome do Motorista</label>
        <input type="text" id="motorista_nome" class="form-control" placeholder="Digite para buscar...">
        <input type="hidden" name="acesso_id" id="acesso_id_hidden" value="{{ old('acesso_id') }}">
    </div>
</div> 