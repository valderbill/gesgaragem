@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Criar Relatório Personalizado de Veículos</h2>

    <form method="POST" action="{{ route('relatorios.veiculos.store') }}">
        @csrf

        <div class="mb-3">
            <label for="nome">Nome do Relatório</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tipos">Tipos de Veículo</label>
            <select name="tipos[]" class="form-select" multiple>
                <option value="OFICIAL">Oficial</option>
                <option value="PARTICULAR">Particular</option>
                <option value="MOTO">Moto</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Salvar Relatório</button>
    </form>
</div>
@endsection
