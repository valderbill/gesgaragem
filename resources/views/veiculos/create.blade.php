@extends('layouts.app')

@section('title', 'Cadastrar Veículo')

@section('content')
    <h1>Cadastrar Veículo</h1>

    {{-- Mensagens de erro --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mensagem de erro genérica --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('veiculos.store') }}" method="POST">
        @csrf

        @include('veiculos.form')

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('veiculos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection

@section('scripts')
    @include('veiculos.form-scripts')
@endsection
