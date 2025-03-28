@extends('layouts.app')

@section('title', 'Afegir Seient')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Afegir Seient</h1>

    <form action="{{ route('seats.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Sessió</label>
            <select name="session_id" class="form-control" required>
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}">{{ $session->id }} - {{ $session->film->title }}</option>
                @endforeach
            </select>
        </div>        
        <div class="mb-3">
            <label class="form-label">Fila</label>
            <input type="text" name="row" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Número</label>
            <input type="number" name="number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tipus</label>
            <select name="type" class="form-control" required>
                <option value="Normal">Normal</option>
                <option value="VIP">VIP</option>
            </select>
        </div>        
        <div class="mb-3">
            <label class="form-label">Estat</label>
            <select name="status" class="form-control" required>
                <option value="lliure">Lliure</option>
                <option value="ocupat">Ocupat</option>
                <option value="reservat">Reservat</option>
            </select>
        </div><br>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('seats.index') }}" class="btn btn-secondary">Cancel·lar</a>
    </form>
</div>
@endsection
