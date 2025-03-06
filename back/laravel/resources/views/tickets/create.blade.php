@extends('layouts.app')

@section('title', 'Afegir Ticket')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Afegir Ticket</h1>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Usuari</label>
            <select name="user_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Sessió</label>
            <select name="session_id" class="form-control" required>
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}">{{ $session->movie->title }} - {{ $session->date }} {{ $session->time }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Seient</label>
            <select name="seat_id" class="form-control" required>
                @foreach($seats as $seat)
                    <option value="{{ $seat->id }}">{{ $seat->number }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Preu</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancel·lar</a>
    </form>
</div>
@endsection
