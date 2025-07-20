@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Alterar Senha</h1>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form action="{{ route('senha.atualizar') }}" method="POST">
        @csrf
        @method('PATCH') 

        <div class="mb-3">
            <label for="senha_atual">Senha Atual</label>
            <input type="password" name="senha_atual" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nova_senha">Nova Senha</label>
            <input type="password" name="nova_senha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nova_senha_confirmation">Confirmar Nova Senha</label>
            <input type="password" name="nova_senha_confirmation" class="form-control" required>
        </div>

        <button class="btn btn-primary">Salvar</button>
    </form>
</div>
@endsection
