<?php

namespace App\Http\Controllers;

use App\Models\Seats;
use Illuminate\Http\Request;

class SeatsController extends Controller
{
    public function index()
    {
        $seients = Seats::all();
        return view('seats.index', compact('seients'));
    }

    public function create()
    {
        return view('seats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'row' => 'required|string|max:10',
            'number' => 'required|integer|min:1',
            'type' => 'required|in:Normal,VIP',
            'status' => 'required|in:lliure,ocupat,reservat',
        ]);

        return Seats::create($validated);
    }


    public function edit(Seats $seient)
    {
        return view('seats.edit', compact('seient'));
    }

    public function update(Request $request, Seats $seient)
    {
        $request->validate([
            'session_id' => 'required|exists:film_sessions,id',
            'row' => 'required|string|max:5',
            'number' => 'required|integer',
            'type' => 'required|string',
            'status' => 'required|in:lliure,ocupat,reservat',
        ]);

        $seient->update($request->all());
        return redirect()->route('seients.index')->with('success', 'Seient actualitzat correctament!');
    }

    public function destroy(Seats $seient)
    {
        $seient->delete();
        return redirect()->route('seients.index')->with('success', 'Seient eliminat correctament!');
    }
}
