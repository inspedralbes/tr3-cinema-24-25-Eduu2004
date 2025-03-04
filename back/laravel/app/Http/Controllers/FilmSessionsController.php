<?php

namespace App\Http\Controllers;

use App\Models\filmSessions;
use Illuminate\Http\Request;

class FilmSessionsController extends Controller
{
    public function index()
    {
        return filmSessions::all();
    }

    public function store(Request $request)
    {
        return filmSessions::create($request->all());
    }

    public function show(filmSessions $session)
    {
        return $session;
    }

    public function update(Request $request, filmSessions $session)
    {
        $session->update($request->all());
        return $session;
    }

    public function destroy(filmSessions $session)
    {
        $session->delete();
        return response()->noContent();
    }
}
