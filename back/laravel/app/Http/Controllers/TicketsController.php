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
            'user_id' => 'required|exists:users,id',
            'session_id' => 'required|exists:film_sessions,id',
            'seat_id' => 'required|exists:seats,id',
            'price' => 'required|numeric',
        ]);

        Tickets::create($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket creado correctamente!');
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
            'user_id' => 'required|exists:users,id',
            'session_id' => 'required|exists:film_sessions,id',
            'seat_id' => 'required|exists:seats,id',
            'price' => 'required|numeric',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket actualizado correctamente!');
    }

    public function destroy(Tickets $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket eliminado correctamente!');
    }
}
