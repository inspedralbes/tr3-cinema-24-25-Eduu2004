@extends('layouts.app')

@section('title', 'Editar Película')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Editar Película</h1>

    <form action="{{ route('movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Títol:</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $movie->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="plot" class="form-label">Sinopsis:</label>
            <textarea class="form-control" name="plot" id="plot" rows="3">{{ old('plot', $movie->plot) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="runtime" class="form-label">Duració (minuts):</label>
            <input type="number" class="form-control" name="runtime" id="runtime" value="{{ old('runtime', $movie->runtime) }}">
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Gènere:</label>
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
        </div><br>

        <button type="submit" class="btn btn-primary">Guardar Canvis</button>
        <a href="{{ route('movies.index') }}" class="btn btn-secondary">Tornar</a>
    </form><br><br>
</div>  
@endsection
