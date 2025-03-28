@extends('layouts.app')

@section('title', 'Editar Seient')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Editar Seient</h1>

    <form action="{{ route('seats.update', $seient->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Fila</label>
            <input type="text" name="row" class="form-control" value="{{ $seient->row }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Número</label>
            <input type="number" name="number" class="form-control" value="{{ $seient->number }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tipus</label>
            <input type="text" name="type" class="form-control" value="{{ $seient->type }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Estat</label>
            <select name="status" class="form-control" required>
                <option value="lliure" {{ $seient->status == 'lliure' ? 'selected' : '' }}>Lliure</option>
                <option value="ocupat" {{ $seient->status == 'ocupat' ? 'selected' : '' }}>Ocupat</option>
                <option value="reservat" {{ $seient->status == 'reservat' ? 'selected' : '' }}>Reservat</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning">Actualitzar</button>
    </form>
</div>
@endsection
