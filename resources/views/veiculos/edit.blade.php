@extends('layouts.app')

@section('content')
    <h1>Editar Veículo</h1>

    <form action="{{ route('veiculos.update', $veiculo->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('veiculos.form')

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('veiculos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
