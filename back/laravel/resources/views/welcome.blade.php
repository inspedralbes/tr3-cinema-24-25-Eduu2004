<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineApp - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero {
            height: 500px;
            background: url('https://source.unsplash.com/1600x900/?cinema') no-repeat center center;
            background-size: cover;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 50px;
        }
        .hero h1 {
            font-size: 3rem;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">🎬 CineApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('movies.index') }}">Películas</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('sessions.index') }}">Funciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('tickets.index') }}">Boletos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('seats.index') }}">Asientos</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
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

    <!-- Footer -->
    <footer class="text-center mt-5 p-3 bg-dark text-light">
        &copy; {{ date('Y') }} CineApp - Todos los derechos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
