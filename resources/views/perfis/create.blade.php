@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Criar Novo Perfil</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('perfis.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nome">Nome do Perfil</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required maxlength="50">
        </div>

        <button type="submit" class="btn btn-success mt-3">Salvar</button>
        <a href="{{ route('perfis.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection
