<?php

namespace App\Http\Controllers;

use App\Models\filmSessions;
use App\Models\Movies;
use App\Models\Seats;
use Illuminate\Http\Request;

class FilmSessionsController extends Controller
{
    // Funció que retorna un llistat de totes les sessions disponibles
    public function index(Request $request)
    {
        // Si la solictud vé de la api retorna les sessions com a un json
        if ($request->is('api/*')) {
            $sessions = filmSessions::with('movie')->get();

            return response()->json([
                'message' => 'Sessions obtained successfully',
                'sessions' => $sessions
            ], 200);
        }

        // Si no és una petició API, es mostrarà un llistat de les sessions a la vista 'sessions.index'
        $sessions = filmSessions::with('movie')->get();
        return view('sessions.index', compact('sessions'));
    }

    // Funció que retorna les sessions que seran pròximament
    public function upcoming(Request $request)
    {
        // Obté les sessions que tenen una data posterior o igual a la data actual, ordenades per data
        $sessions = filmSessions::with('movie')
            ->whereDate('date', '>=', now())
            ->orderBy('date')
            ->get();

        // Retorna les sessions pròximes com una resposta JSON
        return response()->json(['data' => $sessions]);
    }

    // Funció per mostrar el formulari de creació de noves sessions
    public function create()
    {
        $movies = Movies::all();
        return view('sessions.create', compact('movies'));
    }

    // Funció per guardar una nova sessió de cinema
    public function store(Request $request)
    {
        // Validació dels camps de la sol·licitud
        $validated = $request->validate([
            'movie_id'        => 'required|exists:movies,id', // ID de la pel·lícula
            'date'            => 'required|date', // Data de la sessió
            'time'            => 'required|date_format:H:i', // L'hora de la sessió
            'vip_enabled'     => 'required|boolean', // Si la sessió té seients VIP
            'is_discount_day' => 'required|boolean', // Si la sessió és dia de l'espectador
        ]);

        // Creació de la nova sessió amb les dades validades
        filmSessions::create($validated);

        // Redirigeix a la pàgina de sessions amb un missatge de confirmació
        return redirect()->route('sessions.index')->with('success', 'Sessió creada correctament!');
    }

    // Funció per mostrar els detalls d'una sessió específica
    public function show(Request $request, $id)
    {
        // Obté la sessió per ID i les seves dades de la pel·lícula associada
        $session = filmSessions::with('movie')->findOrFail($id);

        // Si la solictud vé de la api retorna la sessió com a json
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
                        'poster' => asset('storage/movies/' . $session->movie->poster), // Asegura la URL de la imatge
                    ],
                ]
            ], 200);
        }

        // Si la sol·licitud no prové de l'API, mostra la vista detallada de la sessió
        return view('sessions.show', compact('session'));
    }

    // Funció per mostrar el formulari d'edició d'una sessió
    public function edit(filmSessions $session)
    {
        // Obté totes les pel·lícules
        $movies = Movies::all();
        return view('sessions.edit', compact('session', 'movies'));
    }

    // Funció per actualitzar les dades d'una sessió existent
    public function update(Request $request, filmSessions $session)
    {
        // Validació dels camps de la sol·licitud
        $validated = $request->validate([
            'movie_id'        => 'required|exists:movies,id', // ID de la pel·lícula 
            'date'            => 'required|date', // Data de la sessió
            'time'            => 'required|date_format:H:i:s', // L'hora de la sessió
            'vip_enabled'     => 'required|boolean', // Si la sessió té seients VIP
            'is_discount_day' => 'required|boolean', // Si la sessió és dia de l'espectador
        ]);

        // Actualitza la sessió amb les dades validades
        $session->update($validated);

        // Redirigeix a la pàgina de sessions amb un missatge d'èxit
        return redirect()->route('sessions.index')->with('success', 'Sessió actualitzada!');
    }

    // Funció per eliminar una sessió
    public function destroy(filmSessions $session)
    {
        // Elimina la sessió de la base de dades
        $session->delete();

        // Redirigeix a la pàgina de sessions amb un missatge de confirmació
        return redirect()->route('sessions.index')->with('success', 'Sessió eliminada.');
    }

    // Funció per obtenir les butaques associades a una sessió
    public function seats(Request $request, $id)
    {
        // Obté totes les butaques associades a la sessió per ID
        $seats = Seats::where('session_id', $id)->get();

        // Retorna les butaques com a json
        return response()->json(['data' => $seats]);
    }

    // Funció per reservar butaques per una sessió
    public function reserveSeats(Request $request, $sessionId)
    {
        // Obté les butaques seleccionades de la sol·licitud
        $selectedSeats = $request->input('seats', []);

        // Actualitza l'estat de les butaques seleccionades a 'Ocupada'
        Seats::where('session_id', $sessionId)
            ->where(function ($query) use ($selectedSeats) {
                foreach ($selectedSeats as $seat) {
                    $query->orWhere(function ($q) use ($seat) {
                        $q->where('row', $seat['row'])
                            ->where('number', $seat['number']);
                    });
                }
            })
            ->update(['status' => 'Ocupada']);

        // Retorna una resposta JSON amb el missatge de confirmació
        return response()->json([
            'message' => 'Asientos reservados correctamente'
        ], 200);
    }
}
