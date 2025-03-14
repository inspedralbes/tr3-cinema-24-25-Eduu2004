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

    public function show(Request $request, $id)
    {
        $session = filmSessions::with('movie')->findOrFail($id);

        if ($request->is('api/*')) {
            return response()->json([
                'message' => 'Sessió trobada',
                'data' => [
                    'session' => $session,
                    'movie' => [
                        'id' => $session->movie->id,
                        'title' => $session->movie->title,
                        'plot' => $session->movie->plot,
                        'runtime' => $session->movie->runtime,
                        'genre' => $session->movie->genre,
                        'poster' => asset('storage/movies/' . $session->movie->poster), // Asegura la URL de la imagen
                    ],
                ]
            ], 200);
        }

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

    public function reserveSeats(Request $request, $sessionId)
{
    $selectedSeats = $request->input('seats', []); // Cada elemento: ['row' => 'L', 'number' => 10]
    \App\Models\Seats::where('session_id', $sessionId)
        ->where(function($query) use ($selectedSeats) {
            foreach ($selectedSeats as $seat) {
                $query->orWhere(function ($q) use ($seat) {
                    $q->where('row', $seat['row'])
                      ->where('number', $seat['number']);
                });
            }
        })
        ->update(['status' => 'Ocupada']);

    return response()->json([
        'message' => 'Asientos reservados correctamente'
    ], 200);
}

}
