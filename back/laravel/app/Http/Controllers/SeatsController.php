<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;

class SeatsController extends Controller
{
    public function index()
    {
        return Seat::all();
    }

    public function store(Request $request)
    {
        return Seat::create($request->all());
    }

    public function show(Seat $seat)
    {
        return $seat;
    }

    public function update(Request $request, Seat $seat)
    {
        $seat->update($request->all());
        return $seat;
    }

    public function destroy(Seat $seat)
    {
        $seat->delete();
        return response()->noContent();
    }
}
