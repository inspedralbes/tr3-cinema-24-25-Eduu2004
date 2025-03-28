@extends('layouts.app')

@section('title', 'Llista de Seients')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Llista de Seients</h1>

    <a href="{{ route('seats.create') }}" class="btn btn-success mb-3">Afegir Seient</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover table-bordered">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Fila</th>
                <th>Número</th>
                <th>Tipus</th>
                <th>Estat</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($seients as $seient)
            <tr>
                <td class="text-center">{{ $seient->id }}</td>
                <td class="text-center">{{ $seient->row }}</td>
                <td class="text-center">{{ $seient->number }}</td>
                <td class="text-center">{{ $seient->type }}</td>
                <td class="text-center">
                    @if($seient->status == 'lliure')
                        <span class="badge bg-success">Lliure</span>
                    @elseif($seient->status == 'ocupat')
                        <span class="badge bg-danger">Ocupat</span>
                    @else
                        <span class="badge bg-warning text-dark">Reservat</span>
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{ route('seats.edit', $seient->id) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                    <form action="{{ route('seats.destroy', $seient->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Segur que vols eliminar aquest seient?')">🗑️ Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
