<?php

namespace App\Http\Controllers;

use App\Models\filmSessions;
use App\Models\Movies; 
use Illuminate\Http\Request;

class FilmSessionsController extends Controller
{
    public function index()
    {
        $sessions = filmSessions::with('movie')->get();
        return view('sessions.index', compact('sessions'));
    }

    public function create()
    {
        $movies = Movies::all();
        return view('sessions.create', compact('movies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'vip_enabled' => 'required|boolean',
            'is_discount_day' => 'required|boolean',
        ]);

        filmSessions::create($validated);
        return redirect()->route('sessions.index')->with('success', 'Sessió creada correctament!');
    }

    public function show(filmSessions $session)
    {
        return view('sessions.show', compact('session'));
    }

    public function edit(filmSessions $session)
    {
        $movies = Movies::all();
        return view('sessions.edit', compact('session', 'movies'));
    }

    public function update(Request $request, filmSessions $session)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s',
            'vip_enabled' => 'required|boolean',
            'is_discount_day' => 'required|boolean',
        ]);

        $session->update($validated);
        return redirect()->route('sessions.index')->with('success', 'Sessió actualitzada!');
    }

    public function destroy(filmSessions $session)
    {
        $session->delete();
        return redirect()->route('sessions.index')->with('success', 'Sessió eliminada.');
    }
}
