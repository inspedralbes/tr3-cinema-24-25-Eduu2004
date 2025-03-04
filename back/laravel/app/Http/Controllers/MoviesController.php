<?php
namespace App\Http\Controllers;

use App\Models\Movies;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
    public function index()
    {
        $movies = Movies::all();
        return view('movies.index', compact('movies'));
    }

    public function create()
    {
        return view('movies.create');
    }

    public function store(Request $request)
    {
        Movies::create($request->all());
        return redirect()->route('movies.index');
    }

    public function show(Movies $movie)
    {
        return view('movies.show', compact('movie'));
    }

    public function edit(Movies $movie)
    {
        return view('movies.edit', compact('movie'));
    }

    public function update(Request $request, Movies $movie)
    {
        $movie->update($request->all());
        return redirect()->route('movies.index');
    }

    public function destroy(Movies $movie)
    {
        $movie->delete();
        return redirect()->route('movies.index');
    }
}
