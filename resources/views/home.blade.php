@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Home</h1>
    <p>Olá, {{ Auth::user()->name }}! Seu perfil é: {{ Auth::user()->perfil }}</p>
</div>
@endsection
