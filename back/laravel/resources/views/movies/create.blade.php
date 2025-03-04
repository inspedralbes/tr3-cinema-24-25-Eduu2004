@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agregar Película</h1>
    <form action="{{ route('movies.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Género</label>
            <input type="text" name="genre" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('movies.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
