@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Películas</h1>
    <a href="{{ route('movies.create') }}" class="btn btn-primary">Agregar Película</a>
    <table class="table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Género</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movies as $movie)
                <tr>
                    <td>{{ $movie->title }}</td>
                    <td>{{ $movie->genre }}</td>
                    <td>
                        <a href="{{ route('movies.show', $movie) }}" class="btn btn-info">Ver</a>
                        <a href="{{ route('movies.edit', $movie) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('movies.destroy', $movie) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('¿Eliminar película?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
