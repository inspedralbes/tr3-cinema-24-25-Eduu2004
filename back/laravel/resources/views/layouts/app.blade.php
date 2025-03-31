<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CineApp')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #1a1a2e, #16213e);
        }

        .navbar-nav .nav-link {
            color: white !important;
            position: relative;
        }

        .navbar-nav .nav-link::after {
            content: "";
            display: block;
            height: 2px;
            width: 0;
            background: #f8b400;
            transition: width 0.3s ease-in-out;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .btn-main {
            background-color: #f8b400;
            color: #1a1a2e;
            font-weight: bold;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        .btn-main:hover {
            background-color: #e09f3e;
            color: white;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">🎬 Cinema</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('movies.index') }}">Pel·lícules</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('sessions.index') }}">Sessions</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('tickets.index') }}">Tiquets</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('seats.index') }}">Seients</a></li>
                </ul>
                <ul class="navbar-nav ms-3">
                    <li class="nav-item">
                        <a href="{{ route('movies.index') }}" class="btn btn-main">Explorar 🎥</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
