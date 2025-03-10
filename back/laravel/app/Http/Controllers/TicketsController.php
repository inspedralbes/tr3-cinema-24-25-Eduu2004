<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use App\Models\filmSessions;
use App\Models\Seats;
use Illuminate\Http\Request;

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
            'user_id'    => 'required|exists:users,id',
            'session_id' => 'required|exists:film_sessions,id',
            'seat_id'    => 'required|exists:seats,id',
            'price'      => 'required|numeric',
            // Opcional: 'is_discount_day' si vols tenir-ho en compte per calcular el preu
        ]);

        // Comprovar si l'usuari ja té una entrada per aquesta sessió
        $existingTicket = Tickets::where('user_id', $validated['user_id'])
            ->where('session_id', $validated['session_id'])
            ->first();
        if ($existingTicket) {
            return redirect()->back()->with('error', 'Ja tens entrades per aquesta sessió.');
        }

        // Verificar que la butaca estigui disponible (suposant que l'estat 'lliure' indica disponibilitat)
        $seat = Seats::find($validated['seat_id']);
        if (!$seat || $seat->status !== 'lliure') {
            return redirect()->back()->with('error', 'La butaca no està disponible.');
        }

        // (Opcional) Calcular el preu en funció del tipus de butaca i si és dia de descompte.
        // Per exemple:
        // if($seat->type === 'VIP'){
        //     $validated['price'] = $request->input('is_discount_day') ? 6 : 8;
        // } else {
        //     $validated['price'] = $request->input('is_discount_day') ? 4 : 6;
        // }

        // Crear el ticket
        $ticket = Tickets::create($validated);

        $seat->update(['status' => 'ocupat']);

        return redirect()->route('tickets.index')->with('success', 'Ticket creat correctament!');
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
            'user_id'    => 'required|exists:users,id',
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
}
