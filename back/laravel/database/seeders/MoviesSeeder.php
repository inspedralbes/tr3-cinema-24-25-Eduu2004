<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Movies;

class MoviesSeeder extends Seeder
{
    public function run()
    {
        $apiKey = env('TMDB_API_KEY');
        $baseUrl = "https://api.themoviedb.org/3";

        for ($page = 1; $page <= 2; $page++) {
            $response = Http::get("$baseUrl/movie/popular", [
                'api_key' => $apiKey,
                'language' => 'es-ES',
                'page' => $page
            ]);

            if ($response->failed()) {
                continue;
            }

            $movies = $response->json()['results'];

            foreach ($movies as $movie) {
                $detailsResponse = Http::get("$baseUrl/movie/{$movie['id']}", [
                    'api_key' => $apiKey,
                    'language' => 'es-ES'
                ]);

                if ($detailsResponse->failed()) {
                    continue;
                }

                $details = $detailsResponse->json();
                $genres = collect($details['genres'])->pluck('name')->implode(', ');

                Movies::create([
                    'title'   => $movie['title'],
                    'plot'    => $movie['overview'] ?: 'Sin descripción',
                    'runtime' => $details['runtime'] ?? 0,
                    'genre'   => $genres ?: 'Desconocido',
                    'poster'  => "https://image.tmdb.org/t/p/w500" . $movie['poster_path'],
                ]);
            }
        }
    }
}