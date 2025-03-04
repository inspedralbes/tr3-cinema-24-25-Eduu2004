@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <!-- Hero Section -->
    <div class="hero" style="
        height: 500px;
        background: url('https://source.unsplash.com/1600x900/?cinema') no-repeat center center;
        background-size: cover;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 50px;">
        <div>
            <h1>Bienvenido a CineApp</h1>
            <p>Explora y administra tus películas, funciones y boletos con facilidad.</p>
            <a href="{{ route('movies.index') }}" class="btn btn-primary btn-lg">Explorar Películas</a>
        </div>
    </div>

    <!-- Sección de Funciones -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">¿Qué puedes hacer?</h2>
        <div class="row text-center">
            <div class="col-md-3">
                <a href="{{ route('movies.index') }}" class="btn btn-outline-dark btn-lg w-100">🎥 Ver Películas</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('sessions.index') }}" class="btn btn-outline-dark btn-lg w-100">📅 Ver Funciones</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-dark btn-lg w-100">🎟️ Administrar Boletos</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('seats.index') }}" class="btn btn-outline-dark btn-lg w-100">💺 Ver Asientos</a>
            </div>
        </div>
    </div>
@endsection
