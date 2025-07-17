@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Editar Usuário</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $usuario->nome) }}" required>
        </div>

        <div class="mb-3">
            <label for="matricula" class="form-label">Matrícula</label>
            <input type="text" class="form-control" id="matricula" name="matricula" value="{{ old('matricula', $usuario->matricula) }}" required>
        </div>

        <div class="mb-3">
            <label for="perfil_id" class="form-label">Perfil</label>
            <select class="form-select" id="perfil_id" name="perfil_id" required>
                @foreach($perfis as $perfil)
                    <option value="{{ $perfil->id }}" {{ old('perfil_id', $usuario->perfil_id) == $perfil->id ? 'selected' : '' }}>
                        {{ $perfil->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Campo Ativo --}}
        <div class="form-check mb-3">
            {{-- Campo oculto para enviar valor 0 caso checkbox não esteja marcado --}}
            <input type="hidden" name="ativo" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="ativo" name="ativo" {{ old('ativo', $usuario->ativo) ? 'checked' : '' }}>
            <label class="form-check-label" for="ativo">
                <i class="fa-solid fa-bolt" style="color: #ffc107;"></i> Ativo
            </label>
        </div>

        {{-- Informações de auditoria --}}
        <div class="mb-3">
            <label class="form-label">Criado por:</label>
            <p class="form-control-plaintext">{{ $usuario->criador->nome ?? 'N/A' }}</p>
        </div>

        <div class="mb-3">
            <label class="form-label">Última Ativação:</label>
            <p class="form-control-plaintext">
                @if($usuario->data_ativacao)
                    {{ $usuario->data_ativacao->format('d/m/Y H:i') }} por {{ $usuario->ativadoPor->nome ?? 'N/A' }}
                @else
                    N/A
                @endif
            </p>
        </div>

        <div class="mb-3">
            <label class="form-label">Última Inativação:</label>
            <p class="form-control-plaintext">
                @if($usuario->data_inativacao)
                    {{ $usuario->data_inativacao->format('d/m/Y H:i') }} por {{ $usuario->inativadoPor->nome ?? 'N/A' }}
                @else
                    N/A
                @endif
            </p>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Voltar</a>
            </div>

            <form method="POST" action="{{ route('usuarios.resetSenha', $usuario->id) }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Tem certeza que deseja redefinir a senha para a matrícula?')">
                    Resetar Senha
                </button>
            </form>
        </div>
    </form>
</div>
@endsection
