@extends('layouts.app')

@section('title', 'Agregar Película')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Afegir Nova Pel·lícula</h1>

    <form action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Títol:</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label for="plot" class="form-label">Sinopsis:</label>
            <textarea class="form-control" name="plot" id="plot" rows="3">{{ old('plot') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="runtime" class="form-label">Duració (minuts):</label>
            <input type="number" class="form-control" name="runtime" id="runtime" value="{{ old('runtime') }}">
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Gènere:</label>
            <input type="text" class="form-control" name="genre" id="genre" value="{{ old('genre') }}">
        </div>

        <div class="mb-3">
            <label for="poster" class="form-label">Poster:</label>
            <input type="file" class="form-control" name="poster" id="poster">
        </div>

        <button type="submit" class="btn btn-success">Guardar Pel·lícula</button>
        <a href="{{ route('movies.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
