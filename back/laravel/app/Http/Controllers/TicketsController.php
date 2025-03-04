<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function index()
    {
        return Tickets::all();
    }

    public function store(Request $request)
    {
        return Tickets::create($request->all());
    }

    public function show(Tickets $ticket)
    {
        return $ticket;
    }

    public function update(Request $request, Tickets $ticket)
    {
        $ticket->update($request->all());
        return $ticket;
    }

    public function destroy(Tickets $ticket)
    {
        $ticket->delete();
        return response()->noContent();
    }
}
