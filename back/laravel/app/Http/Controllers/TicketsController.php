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
    // Si la petición es a la API, filtra por email
    if ($request->is('api/*')) {
        $email = $request->query('email');
        if (!$email) {
            return response()->json(['error' => 'Email is required'], 400);
        }

        // Obtiene los tickets con la sesión y la película
        $tickets = Tickets::where('email', $email)
            ->with(['session.movie']) // Asegurar que session y movie están cargados
            ->get();

        // Decodifica los asientos de JSON a un array
        foreach ($tickets as $ticket) {
            $ticket->seats = json_decode($ticket->seats, true);
        }

        return response()->json(['data' => $tickets]);
    }

    // Para la vista en Blade
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

        // Obtiene la sesión con la información de la película
        $session = filmSessions::with('movie')->findOrFail($validated['session_id']);

        $ticketsCreated = [];

        // Itera sobre cada asiento seleccionado para crear un ticket individual
        foreach ($validated['seats'] as $seat) {
            // Verifica si ya existe un ticket para este asiento y correo en esta sesión
            $exists = Tickets::where('email', $validated['email'])
                ->where('session_id', $validated['session_id'])
                ->whereJsonContains('seats', $seat)
                ->exists();
            if ($exists) {
                return response()->json(['error' => 'Ya tienes un ticket para este asiento en esta sesión.'], 400);
            }

            // Asegúrate de guardar el campo 'seats' como un array y no como un string JSON
            $ticket = Tickets::create([
                'email'      => $validated['email'],
                'session_id' => $validated['session_id'],
                'seats'      => json_encode([$seat]), // Guarda como JSON correctamente
                'price'      => $validated['price'] / count($validated['seats']),
            ]);

            $ticketsCreated[] = $ticket;

            // Prepara los datos para el correo del ticket individual
            $ticketData = [
                'seat'   => $seat, // $seat es un array con 'row' y 'number'
                'time'   => $session->time,
                'date'   => $session->date,
                'movie'  => $session->movie->title,
            ];

            // Enviar el correo al usuario
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

        // Obtener la sesión con la información de la película
        $session = filmSessions::with('movie')->findOrFail($validated['session_id']);
        $ticketsCreated = [];

        foreach ($validated['seats'] as $seat) {
            // Verificar si este asiento ya tiene ticket para este correo electrónico y sesión
            $exists = Tickets::where('email', $validated['email'])
                ->where('session_id', $validated['session_id'])
                ->whereJsonContains('seats', $seat)
                ->exists();

            if ($exists) {
                return response()->json(['error' => 'Ya tienes un ticket para este asiento en esta sesión.'], 400);
            }

            // Calcular el precio según el tipo de asiento: si la fila es "F", es VIP (8€); sino, 6€
            $ticketPrice = ($seat['row'] === 'F') ? 8 : 6;

            // Crear un ticket para este asiento
            $ticket = Tickets::create([
                'email'      => $validated['email'],
                'session_id' => $validated['session_id'],
                'seats'      => json_encode([$seat]),
                'price'      => $ticketPrice,
            ]);
            $ticketsCreated[] = $ticket;

            // Actualizar el estado del asiento en la base de datos
            Seats::where('session_id', $validated['session_id'])
                ->where('row', $seat['row'])
                ->where('number', $seat['number'])
                ->update(['status' => 'Ocupada']);

            // Preparar los datos para el correo electrónico, incluyendo la ID del ticket
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
        
        // Obtener todos los tickets para ese correo electrónico
        $tickets = Tickets::where('email', $validated['email'])
            ->with(['session.movie', 'seat'])  // Asegúrate de cargar tanto la relación 'session' como 'movie' 
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
