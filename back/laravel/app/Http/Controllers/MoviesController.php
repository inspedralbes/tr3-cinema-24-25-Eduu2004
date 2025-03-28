<?php
namespace App\Http\Controllers;

use App\Models\Movies;
use Illuminate\Http\Request;

class MoviesController extends Controller
{

    public function index(Request $request) {
        if ($request->is('api/*')) {
            $movies = Movies::all();

            return response()->json([
                'message' => 'Películas obtenidas con éxito',
                'movies' => $movies,
            ], 200);
        }

        $movies = Movies::all();
        return view('movies.index', compact('movies'));
    }


    public function create()
    {
        return view('movies.create');
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'plot' => 'nullable|string',
            'runtime' => 'nullable|integer',
            'genre' => 'nullable|string|max:100',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $movie = new Movies($request->except('poster'));
    
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
            $movie->poster = $posterPath;
        }
    
        $movie->save();
    
        return redirect()->route('movies.index')->with('success', 'Película agregada con éxito.');
    }
    

    public function show(Request $request, $id)
    {
        // Verificar si la solicitud es desde una API
        if ($request->is('api/*')) {
            // Buscar la película por ID
            $movie = Movies::find($id);

            // Verificar si la película existe
            if (!$movie) {
                return response()->json(['message' => 'Película no encontrada'], 404);
            }

            // Devolver la película en formato JSON
            return response()->json([
                'movie' => $movie
            ]);
        }

        // Si no es una solicitud API, devolver una vista
        $movie = Movies::find($id);
        return view('movies.show', compact('movie'));
    }

    public function edit(Movies $movie) {
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
