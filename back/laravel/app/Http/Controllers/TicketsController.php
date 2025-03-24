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
    public function index(Request $request)
    {
        if ($request->is('api/*')) {
            $email = $request->query('email');
            if (!$email) {
                return response()->json(['error' => 'Email is required'], 400);
            }

            $tickets = Tickets::where('email', $email)
                ->with(['session.movie'])
                ->get();

            foreach ($tickets as $ticket) {
                $ticket->seats = json_decode($ticket->seats, true);
            }

            return response()->json(['data' => $tickets]);
        }

        $tickets = Tickets::with(['session.movie'])->get();
        foreach ($tickets as $ticket) {
            $ticket->seats = json_decode($ticket->seats, true);
        }

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

        $session = filmSessions::with('movie')->findOrFail($validated['session_id']);

        $ticketsCreated = [];

        foreach ($validated['seats'] as $seat) {
            $exists = Tickets::where('email', $validated['email'])
                ->where('session_id', $validated['session_id'])
                ->whereJsonContains('seats', $seat)
                ->exists();
            if ($exists) {
                return response()->json(['error' => 'Ya tienes un ticket para este asiento en esta sesión.'], 400);
            }

            $ticket = Tickets::create([
                'email'      => $validated['email'],
                'session_id' => $validated['session_id'],
                'seats'      => json_encode([$seat]),
                'price'      => $validated['price'] / count($validated['seats']),
            ]);

            $ticketsCreated[] = $ticket;

            $ticketData = [
                'seat'   => $seat,
                'time'   => $session->time,
                'date'   => $session->date,
                'movie'  => $session->movie->title,
            ];

            Mail::to($validated['email'])->send(new TicketMail($ticketData));
        }

        return response()->json(['message' => 'Tickets creados correctamente!', 'tickets' => $ticketsCreated], 200);
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

        return redirect()->route('tickets.index')->with('success', 'Ticket actualizado correctamente!');
    }

    public function destroy(Tickets $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket eliminado correctamente!');
    }

    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'email'      => 'required|string|email',
            'session_id' => 'required|exists:film_sessions,id',
            'seats'      => 'required|array|min:1|max:10',
        ]);

        $session = filmSessions::with('movie')->findOrFail($validated['session_id']);
        $ticketsCreated = [];

        foreach ($validated['seats'] as $seat) {
            $exists = Tickets::where('email', $validated['email'])
                ->where('session_id', $validated['session_id'])
                ->whereJsonContains('seats', $seat)
                ->exists();

            if ($exists) {
                return response()->json(['error' => 'Ya tienes un ticket para este asiento en esta sesión.'], 400);
            }

            $ticketPrice = ($seat['row'] === 'F') ? 8 : 6;

            $ticket = Tickets::create([
                'email'      => $validated['email'],
                'session_id' => $validated['session_id'],
                'seats'      => json_encode([$seat]),
                'price'      => $ticketPrice,
            ]);
            $ticketsCreated[] = $ticket;

            Seats::where('session_id', $validated['session_id'])
                ->where('row', $seat['row'])
                ->where('number', $seat['number'])
                ->update(['status' => 'Ocupada']);

            $ticketData = [
                'id'     => $ticket->id,
                'seat'   => $seat,
                'time'   => $session->time,
                'date'   => $session->date,
                'movie'  => $session->movie->title,
                'price'  => $ticketPrice,
            ];

            Mail::to($validated['email'])->send(new TicketMail($ticketData));
        }

        return response()->json(['message' => 'Compra realizada con éxito! Recibirás un correo con tu ticket.'], 200);
    }

    public function getTicketsByEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        $tickets = Tickets::where('email', $validated['email'])
            ->with(['session.movie', 'seat'])
            ->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No se han encontrado tickets para este correo.'], 404);
        }

        return response()->json(['data' => $tickets], 200);
    }

    public function getSessionTickets($sessionId)
    {
        $session = filmSessions::with(['tickets', 'movie'])->findOrFail($sessionId);
        return response()->json(['data' => $session]);
    }
}
