@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Selecionar Estacionamento</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('definir.estacionamento') }}">
        @csrf

        <div class="mb-3">
            <label for="estacionamento_id" class="form-label">Estacionamento</label>
            <select name="estacionamento_id" id="estacionamento_id" class="form-select" required>
                <option value="">Selecione</option>
                @foreach($estacionamentos as $est)
                    <option value="{{ $est->id }}">{{ $est->localizacao }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Definir Estacionamento</button>
    </form>
</div>
@endsection
