@extends('layouts.app')

@section('title', 'Editar Película')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Editar Película</h1>

    <form action="{{ route('movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Título:</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $movie->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="plot" class="form-label">Sinopsis:</label>
            <textarea class="form-control" name="plot" id="plot" rows="3">{{ old('plot', $movie->plot) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="runtime" class="form-label">Duración (minutos):</label>
            <input type="number" class="form-control" name="runtime" id="runtime" value="{{ old('runtime', $movie->runtime) }}">
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Género:</label>
            <input type="text" class="form-control" name="genre" id="genre" value="{{ old('genre', $movie->genre) }}">
        </div>

        <div class="mb-3">
            <label for="poster" class="form-label">Poster:</label>
            <input type="file" class="form-control" name="poster" id="poster">
            @if($movie->poster)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $movie->poster) }}" alt="Poster" width="100">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="created_at" class="form-label">Creado el:</label>
            <input type="text" class="form-control" value="{{ $movie->created_at->format('d/m/Y H:i') }}" disabled>
        </div>

        <div class="mb-3">
            <label for="updated_at" class="form-label">Última actualización:</label>
            <input type="text" class="form-control" value="{{ $movie->updated_at->format('d/m/Y H:i') }}" disabled>
        </div>

        <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
        <a href="{{ route('movies.index') }}" class="btn btn-secondary">🔙 Volver</a>
    </form>
</div>
@endsection
