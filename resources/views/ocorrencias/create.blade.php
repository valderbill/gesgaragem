@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Criar Ocorrência</h1>

    {{-- Mostrar nome do usuário autenticado --}}
    @if(Auth::check())
        <p><strong>Usuário:</strong> {{ Auth::user()->name }}</p>
    @else
        <p><strong>Usuário não localizado</strong></p>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('ocorrencias.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição da Ocorrência</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="4" required>{{ old('descricao') }}</textarea>  <!-- Alterado para 'descricao' -->
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('ocorrencias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
