@extends('layouts.app')

@section('title', 'Editar Sessió')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Editar Sessió</h1>

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario de edición -->
    <form action="{{ route('sessions.update', $session->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Pel·lícula</label>
            <select name="movie_id" class="form-control" required>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}" {{ $movie->id == $session->movie_id ? 'selected' : '' }}>
                        {{ $movie->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Data</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', $session->date) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="time" class="form-control" value="{{ old('time', $session->time) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">VIP</label>
            <select name="vip_enabled" class="form-control" required>
                <option value="1" {{ $session->vip_enabled ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$session->vip_enabled ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Dia de Descompte</label>
            <select name="is_discount_day" class="form-control" required>
                <option value="1" {{ $session->is_discount_day ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$session->is_discount_day ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualitzar</button>
        <a href="{{ route('sessions.index') }}" class="btn btn-secondary">Cancel·lar</a>
    </form><br><br>
</div>
@endsection
