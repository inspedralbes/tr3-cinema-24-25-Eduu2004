@extends('layouts.app')

@section('title', 'Afegir Sessió')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Afegir Sessió</h1>

    <form action="{{ route('sessions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Pel·lícula</label>
            <select name="movie_id" class="form-control" required>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Data</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="time" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">VIP</label>
            <select name="vip_enabled" class="form-control" required>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Dia de Descompte</label>
            <select name="is_discount_day" class="form-control" required>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('sessions.index') }}" class="btn btn-secondary">Cancel·lar</a>
    </form><br><br>
</div>
@endsection
