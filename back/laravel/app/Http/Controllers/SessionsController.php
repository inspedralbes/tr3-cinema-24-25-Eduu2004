<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function index()
    {
        return Session::all();
    }

    public function store(Request $request)
    {
        return Session::create($request->all());
    }

    public function show(Session $session)
    {
        return $session;
    }

    public function update(Request $request, Session $session)
    {
        $session->update($request->all());
        return $session;
    }

    public function destroy(Session $session)
    {
        $session->delete();
        return response()->noContent();
    }
}
