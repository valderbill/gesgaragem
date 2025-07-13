@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Relatório: {{ $relatorio->nome }}</h2>

    <form method="POST" action="{{ route('relatorios.veiculos.update', $relatorio->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" value="{{ $relatorio->nome }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tipos</label>
            <select name="tipos[]" class="form-select" multiple>
                <option value="OFICIAL" @selected(in_array('OFICIAL', $relatorio->tipos))>Oficial</option>
                <option value="PARTICULAR" @selected(in_array('PARTICULAR', $relatorio->tipos))>Particular</option>
                <option value="MOTO" @selected(in_array('MOTO', $relatorio->tipos))>Moto</option>
            </select>
        </div>

        <button class="btn btn-primary">Atualizar</button>
    </form>
</div>
@endsection
