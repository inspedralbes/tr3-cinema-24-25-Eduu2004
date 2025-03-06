@extends('layouts.app')

@section('title', 'Sessions de Pel·lícules')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Llista de Sessions</h1>

    <a href="{{ route('sessions.create') }}" class="btn btn-success mb-3">Afegir Sessió</a>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Pel·lícula</th>
                <th>Data</th>
                <th>Hora</th>
                <th>VIP</th>
                <th>Descompte</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sessions as $session)
            <tr>
                <td>{{ $session->id }}</td>
                <td>{{ $session->movie->title }}</td>
                <td>{{ $session->date }}</td>
                <td>{{ $session->time }}</td>
                <td>{{ $session->vip_enabled ? '✔️ Sí' : '❌ No' }}</td>
                <td>{{ $session->is_discount_day ? '✔️ Sí' : '❌ No' }}</td>
                <td>
                    <a href="{{ route('sessions.edit', $session->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('sessions.destroy', $session->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Segur que vols eliminar aquesta sessió?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
