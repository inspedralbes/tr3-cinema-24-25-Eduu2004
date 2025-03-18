<?php

namespace App\Http\Controllers;

use App\Mail\TicketMail;
use App\Models\Tickets;
use App\Models\filmSessions;
use App\Models\Seats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketsController extends Controller
{
    public function index()
    {
        $tickets = Tickets::with(['session', 'seat'])->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $sessions = filmSessions::all();
        $seats = Seats::all();
        return view('tickets.create', compact('sessions', 'seats'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'email'      => 'required|string|email',
        'session_id' => 'required|exists:film_sessions,id',
        'seats'      => 'required|array|min:1|max:10',
        'price'      => 'required|numeric',
    ]);

    // Obté la sessió amb la informació de la pel·lícula
    $session = filmSessions::with('movie')->findOrFail($validated['session_id']);

    $ticketsCreated = [];

    // Iterem sobre cada seient seleccionat per crear un ticket individual
    foreach ($validated['seats'] as $seat) {
        // Comprovem si ja existeix un ticket per aquest seient per a aquest email i sessió (opcional)
        $exists = Tickets::where('email', $validated['email'])
            ->where('session_id', $validated['session_id'])
            ->whereJsonContains('seats', $seat)
            ->exists();
        if ($exists) {
            return response()->json(['error' => 'Ja tens una entrada per aquest seient en aquesta sessió.'], 400);
        }

        // Crea un tiquet per aquest seient (guardant-lo com a array amb un sol element)
        $ticket = Tickets::create([
            'email'      => $validated['email'],
            'session_id' => $validated['session_id'],
            'seats'      => json_encode([$seat]),
            // Dividim el preu total pel nombre de seients per assignar el preu per ticket
            'price'      => $validated['price'] / count($validated['seats']),
        ]);

        $ticketsCreated[] = $ticket;

        // Prepara les dades per al correu per aquest tiquet individual
        $ticketData = [
            'seat'   => $seat, // s'espera que $seat sigui un array amb 'row' i 'number'
            'time'   => $session->time,
            'date'   => $session->date,
            'movie'  => $session->movie->title,
        ];

        Mail::to($validated['email'])->send(new TicketMail($ticketData));
    }

    return response()->json(['message' => 'Tiquets creats correctament!', 'tickets' => $ticketsCreated], 200);
}



    public function show(Tickets $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Tickets $ticket)
    {
        $sessions = filmSessions::all();
        $seats = Seats::all();
        return view('tickets.edit', compact('ticket', 'sessions', 'seats'));
    }

    public function update(Request $request, Tickets $ticket)
    {
        $validated = $request->validate([
            'email'      => 'required|string|email',
            'session_id' => 'required|exists:film_sessions,id',
            'seat_id'    => 'required|exists:seats,id',
            'price'      => 'required|numeric',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket actualitzat correctament!');
    }

    public function destroy(Tickets $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket eliminat correctament!');
    }

    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'email'      => 'required|string|email',
            'session_id' => 'required|exists:film_sessions,id',
            'seats'      => 'required|array|min:1|max:10',
            'price'      => 'required|numeric',
        ]);

        $session = filmSessions::with('movie')->findOrFail($validated['session_id']);

        $ticketsCreated = [];

        foreach ($validated['seats'] as $seat) {
            $exists = Tickets::where('email', $validated['email'])
                ->where('session_id', $validated['session_id'])
                ->whereJsonContains('seats', $seat)
                ->exists();
            if ($exists) {
                return response()->json(['error' => 'Ja tens una entrada per aquest seient en aquesta sessió.'], 400);
            }

            $ticket = Tickets::create([
                'email'      => $validated['email'],
                'session_id' => $validated['session_id'],
                'seats'      => json_encode([$seat]),
                'price'      => $validated['price'] / count($validated['seats']),
            ]);
            $ticketsCreated[] = $ticket;

            Seats::where('session_id', $validated['session_id'])
                ->where('row', $seat['row'])
                ->where('number', $seat['number'])
                ->update(['status' => 'Ocupada']);

            $ticketData = [
                'seat'   => $seat,
                'time'   => $session->time,
                'date'   => $session->date,
                'movie'  => $session->movie->title,
            ];

            Mail::to($validated['email'])->send(new TicketMail($ticketData));
        }

        return response()->json(['message' => 'Compra realitzada amb èxit! Rebràs un correu amb el teu tiquet.'], 200);
    }


    
}
