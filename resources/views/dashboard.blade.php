@extends('layouts.app')

@section('content')
    <style>
        .img-destaque {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
        }
    </style>

    <div class="container text-center">
        <img src="{{ asset('images/foto.png') }}"
             alt="Imagem destaque"
             class="img-fluid rounded shadow-sm img-destaque"
             style="max-width: 600px;">
    </div>
@endsection
