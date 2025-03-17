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
            'email'    => 'required|string',
            'session_id' => 'required|exists:film_sessions,id',
            'seats'      => 'required|array|min:1|max:10', 
            'price'      => 'required|numeric',
        ]);

        $existingTicket = Tickets::where('user_id', $validated['user_id'])
            ->where('session_id', $validated['session_id'])
            ->first();
        if ($existingTicket) {
            return redirect()->back()->with('error', 'Ja tens entrades per aquesta sessió.');
        }


        $ticket = Tickets::create([
            'email'    => $validated['email'],
            'session_id' => $validated['session_id'],
            'seats'      => json_encode($validated['seats']),
            'price'      => $validated['price']
        ]);

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
            'email'    => 'required|string',
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
