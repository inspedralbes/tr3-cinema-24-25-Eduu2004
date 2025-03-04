<?php

namespace App\Http\Controllers;

use App\Models\Seats;
use Illuminate\Http\Request;

class SeatsController extends Controller
{
    public function index()
    {
        return Seats::all();
    }

    public function store(Request $request)
    {
        return Seats::create($request->all());
    }

    public function show(Seats $seat)
    {
        return $seat;
    }

    public function update(Request $request, Seats $seat)
    {
        $seat->update($request->all());
        return $seat;
    }

    public function destroy(Seats $seat)
    {
        $seat->delete();
        return response()->noContent();
    }
}
