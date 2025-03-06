@extends('layouts.app')

@section('title', 'Películas')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Llistat de Pel·lícules</h1>
    
    <a href="{{ route('movies.create') }}" class="btn btn-success mb-3">Afegir Pel·lícules</a>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Títol</th>
                <th>Sinopsis</th>
                <th>Duració (min)</th>
                <th>Gènere</th>
                <th>Poster</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movies as $movie)
            <tr>
                <td>{{ $movie->id }}</td>
                <td>{{ $movie->title }}</td>
                <td>{{ Str::limit($movie->plot, 300) }}</td>
                <td>{{ $movie->runtime }}</td>
                <td>{{ $movie->genre }}</td>
                <td>
                    @if($movie->poster)
                        <img src="{{ asset('storage/' . $movie->poster) }}" alt="Poster" width="50">
                    @else
                        No disponible
                    @endif
                </td>
                <td>
                    <a href="{{ route('movies.edit', $movie->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('movies.destroy', $movie->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta película?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
