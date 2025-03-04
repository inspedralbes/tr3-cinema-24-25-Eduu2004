@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Película</h1>
    <form action="{{ route('movies.update', $movie) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="title" value="{{ $movie->title }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Género</label>
            <input type="text" name="genre" value="{{ $movie->genre }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('movies.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
