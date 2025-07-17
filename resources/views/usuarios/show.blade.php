@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Detalhes do Usuário</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $usuario->nome }}</h5>
            <p class="card-text"><strong>Matrícula:</strong> {{ $usuario->matricula }}</p>
            <p class="card-text"><strong>Perfil:</strong> {{ $usuario->perfil->nome ?? 'N/A' }}</p>

            {{-- Status do usuário --}}
            <p class="card-text"><strong>Status:</strong> 
                @if($usuario->ativo)
                    <span class="badge bg-success">Ativo</span>
                @else
                    <span class="badge bg-secondary">Inativo</span>
                @endif
            </p>

            {{-- Informações de auditoria --}}
            <p class="card-text"><strong>Criado por:</strong> {{ $usuario->criador->nome ?? 'N/A' }}</p>
            <p class="card-text"><strong>Última Ativação:</strong> 
                @if($usuario->data_ativacao)
                    {{ $usuario->data_ativacao->format('d/m/Y H:i') }} por {{ $usuario->ativadoPor->nome ?? 'N/A' }}
                @else
                    N/A
                @endif
            </p>
            <p class="card-text"><strong>Última Inativação:</strong> 
                @if($usuario->data_inativacao)
                    {{ $usuario->data_inativacao->format('d/m/Y H:i') }} por {{ $usuario->inativadoPor->nome ?? 'N/A' }}
                @else
                    N/A
                @endif
            </p>

            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-3">Voltar</a>
        </div>
    </div>
</div>
@endsection
