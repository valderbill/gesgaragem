@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Lista de Usuários</h1>
    <a href="{{ route('usuarios.create') }}" class="btn btn-success mb-3">Cadastrar Novo</a>

    {{-- Exibir mensagem de sucesso geral --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Exibir nova senha para o usuário resetado --}}
    @if(session('novaSenha') && session('usuarioId'))
        <div class="alert alert-info">
            Senha redefinida para o usuário
            <strong>
                {{ $usuarios->firstWhere('id', session('usuarioId'))->nome ?? 'Usuário' }}
            </strong>:
            <code>{{ session('novaSenha') }}</code>
        </div>
    @endif

    <script>
        function alterarStatus(formId) {
            document.getElementById(formId).submit();
        }
    </script>

    <style>
        .w-10 { width: 10%; }
        .w-15 { width: 15%; }
    </style>

    <table class="table table-bordered align-middle" style="table-layout: fixed;">
        <thead>
            <tr>
                <th style="width: 35%;">Nome</th>
                <th class="w-15">Matrícula</th>
                <th class="w-15">Perfil</th>
                <th class="w-15">Ações</th>
                <th class="w-15">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td style="padding: 0.5rem;">{{ $usuario->nome }}</td>
                    <td style="padding: 0.3rem; white-space: nowrap;">{{ $usuario->matricula }}</td>
                    <td style="padding: 0.3rem; white-space: nowrap;">{{ $usuario->perfil->nome ?? 'N/A' }}</td>
                    <td style="padding: 0.3rem; white-space: nowrap;">
                        <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn btn-info btn-sm px-2 py-1">Ver</a>
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning btn-sm px-2 py-1">Editar</a>
                        <form action="{{ route('usuarios.resetSenha', $usuario->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm px-2 py-1"
                                onclick="return confirm('Tem certeza que deseja redefinir a senha para a matrícula?')">
                                Resetar Senha
                            </button>
                        </form>
                    </td>
                    <td style="padding: 0.3rem; white-space: nowrap;">
                        <form id="status-form-{{ $usuario->id }}" action="{{ route('usuarios.alternar-status', $usuario->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <div class="form-check form-check-inline" style="margin-bottom: 0;">
                                <input class="form-check-input" type="radio" name="ativo" id="ativo-{{ $usuario->id }}" value="1"
                                    onchange="alterarStatus('status-form-{{ $usuario->id }}')" {{ $usuario->ativo ? 'checked' : '' }}>
                                <label class="form-check-label" for="ativo-{{ $usuario->id }}" style="font-size: 0.85rem; margin-right: 0.5rem;">Ativo</label>
                            </div>
                            <div class="form-check form-check-inline" style="margin-bottom: 0;">
                                <input class="form-check-input" type="radio" name="ativo" id="inativo-{{ $usuario->id }}" value="0"
                                    onchange="alterarStatus('status-form-{{ $usuario->id }}')" {{ !$usuario->ativo ? 'checked' : '' }}>
                                <label class="form-check-label" for="inativo-{{ $usuario->id }}" style="font-size: 0.85rem;">Inativo</label>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
