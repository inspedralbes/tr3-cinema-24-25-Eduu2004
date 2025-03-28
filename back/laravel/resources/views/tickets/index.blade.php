@extends('layouts.app')

@section('title', 'Llista de Tickets')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Llista de Tickets</h1>

    <a href="{{ route('tickets.create') }}" class="btn btn-success mb-3">Afegir Ticket</a>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuari</th>
                <th>Pel·lícula</th>
                <th>Seient</th>
                <th>Preu</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->email }}</td>
                <td>{{ $ticket->session ? $ticket->session->movie->title : 'No disponible' }}</td>
                <td>
                    @if (!empty($ticket->seats))
                        @foreach ($ticket->seats as $seat)
                            {{ $seat['row'] }}-{{ $seat['number'] }}
                        @endforeach
                    @else
                        No asignado
                    @endif
                </td>
                <td>{{ $ticket->price }}</td>
                <td>
                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Segur que vols eliminar aquest ticket?')">🗑️ Borrar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
