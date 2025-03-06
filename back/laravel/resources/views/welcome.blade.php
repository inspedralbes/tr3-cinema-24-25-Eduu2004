@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<style>
    .hero {
        background: linear-gradient(90deg, #1a1a2e, #16213e);
        color: white;
        text-align: center;
        padding: 80px 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero h1 {
        font-size: 48px;
        font-weight: bold;
    }

    .hero p {
        font-size: 18px;
        margin-top: 10px;
    }

    .btn-main {
        background-color: #f8b400;
        color: #1a1a2e;
        font-size: 18px;
        padding: 12px 24px;
        font-weight: bold;
        border-radius: 5px;
        transition: all 0.3s ease-in-out;
    }

    .btn-main:hover {
        background-color: #e09f3e;
        color: white;
        transform: scale(1.05);
        transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
    }

    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="hero">
    <div>
        <h1>Benvingut al del Cinema!</h1>
        <p>Explora i administra pel·lícules, sessions i els tiquets amb facilitat.</p>
        <a href="{{ route('movies.index') }}" class="btn btn-main">Explorar Películas</a>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center mb-4">Què pots fer?</h2>
    <div class="row text-center justify-content-center">
        <div class="col-md-3 mb-3">
            <div class="card p-3">
                <h3>🎥 Pel·lícules</h3>
                <p>Consulta i administra la cartellera del Cinema.</p>
                <a href="{{ route('movies.index') }}" class="btn btn-dark">Mirar Pel·lícules</a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card p-3">
                <h3>📅 Sessions</h3>
                <p>Consulta horaris i sessions disponibles.</p>
                <a href="{{ route('sessions.index') }}" class="btn btn-dark">Mirar Sessions</a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card p-3">
                <h3>🎟️ Tiquets</h3>
                <p>Administra la compra de tiquets fàcilmente.</p>
                <a href="{{ route('tickets.index') }}" class="btn btn-dark">Mirar Tiquets</a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card p-3">
                <h3>💺 Seients</h3>
                <p>Revisa i selecciona els seients per cada perl·lícula.</p>
                <a href="{{ route('seats.index') }}" class="btn btn-dark">Mirar Seients</a>
            </div>
        </div>
    </div>
</div>
@endsection
