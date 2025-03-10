<?php

namespace App\Http\Controllers;

use App\Models\filmSessions;
use App\Models\Movies;
use App\Models\Seats;
use Illuminate\Http\Request;

class FilmSessionsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->is('api/*')) {
            $sessions = filmSessions::with('movie')->get();

            return response()->json([
                'message' => 'Sessions obtained successfully',
                'sessions' => $sessions
            ], 200);
        }

        $sessions = filmSessions::with('movie')->get();
        return view('sessions.index', compact('sessions'));
    }


    public function upcoming(Request $request)
    {
        $sessions = filmSessions::with('movie')
            ->whereDate('date', '>=', now())
            ->orderBy('date')
            ->get();
        return response()->json(['data' => $sessions]);
    }

    public function create()
    {
        $movies = Movies::all();
        return view('sessions.create', compact('movies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id'        => 'required|exists:movies,id',
            'date'            => 'required|date',
            'time'            => 'required|date_format:H:i',
            'vip_enabled'     => 'required|boolean',
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
            'movie_id'        => 'required|exists:movies,id',
            'date'            => 'required|date',
            'time'            => 'required|date_format:H:i:s',
            'vip_enabled'     => 'required|boolean',
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

    public function seats(Request $request, $id)
    {
        $seats = Seats::where('session_id', $id)->get();
        return response()->json(['data' => $seats]);
    }
}
