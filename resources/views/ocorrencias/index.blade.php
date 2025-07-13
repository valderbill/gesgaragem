@extends('layouts.app')

@section('content')
@php
    /** @var \Illuminate\Support\Str $Str */
    use Illuminate\Support\Str;
@endphp

<div class="container">
    <h1 class="mb-4">Ocorrências</h1>

    {{-- Exibir nome do usuário logado --}}
    @auth
        <p><strong>Usuário logado:</strong> {{ Auth::user()->nome }}</p>
    @else
        <p class="text-danger"><strong>Usuário não localizado</strong></p>
    @endauth

    <a href="{{ route('ocorrencias.create') }}" class="btn btn-primary mb-3">
        Nova Ocorrência
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th style="width:45%">Ocorrência</th>
                <th style="width:20%">Horário</th>
                <th style="width:25%">Usuário</th>
                <th style="width:10%">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ocorrencias as $ocorrencia)
                <tr>
                    {{-- Limita o texto a 50 caracteres sem quebrar HTML --}}
                    <td>{{ Str::limit($ocorrencia->ocorrencia, 50) }}</td>

                    {{-- Exibe a data formatada --}}
                    <td>{{ optional($ocorrencia->horario)->format('d/m/Y H:i:s') }}</td>

                    {{-- Nome do usuário associado --}}
                    <td>{{ optional($ocorrencia->usuario)->nome ?? 'Usuário não encontrado' }}</td>

                    <td class="text-center">
                        {{-- Botão Visualizar --}}
                        <a href="{{ route('ocorrencias.show', $ocorrencia->id) }}"
                           class="btn btn-primary btn-sm mb-1">
                            Visualizar
                        </a>

                        {{-- Botão Incluir (editar) --}}
                        <a href="{{ route('ocorrencias.edit', $ocorrencia->id) }}"
                           class="btn btn-success btn-sm">
                            Incluir
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Nenhuma ocorrência encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginação --}}
    <div class="d-flex justify-content-center">
        {{ $ocorrencias->links() }}
    </div>
</div>
@endsection
