@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Motoristas</h1>

    <a href="{{ route('motoristas.create') }}" class="btn btn-success mb-3">Cadastrar Novo Motorista</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($motoristas->count() > 0)
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    @if(strtolower(Auth::user()->perfil->nome) === 'administrador')
                        <th>Status</th>
                        <th>Ações</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($motoristas as $motorista)
                    <tr>
                        <td>
                            @if($motorista->foto)
                                <img src="{{ asset('storage/' . $motorista->foto) }}" width="80" height="80" alt="Foto" class="rounded">
                            @else
                                <span>Sem foto</span>
                            @endif
                        </td>
                        <td>{{ $motorista->nome }}</td>
                        <td>{{ $motorista->matricula }}</td>

                        @if(strtolower(Auth::user()->perfil->nome) === 'administrador')
                            <td>
                                <form action="{{ route('motoristas.alternar-status', $motorista->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="form-check-label">
                                            <input type="radio" name="ativo" value="1" onchange="this.form.submit()" {{ $motorista->ativo ? 'checked' : '' }}>
                                            Ativo
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" name="ativo" value="0" onchange="this.form.submit()" {{ !$motorista->ativo ? 'checked' : '' }}>
                                            Inativo
                                        </label>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('motoristas.show', $motorista->id) }}" class="btn btn-info btn-sm">Visualizar</a>
                                <a href="{{ route('motoristas.edit', $motorista->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Nenhum motorista cadastrado.</p>
    @endif
</div>
@endsection
