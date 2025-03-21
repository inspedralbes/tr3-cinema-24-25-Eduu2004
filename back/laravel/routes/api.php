<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmSessionsController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\SeatsController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\AuthController;


Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::post('/tickets/purchase', [TicketsController::class, 'purchase']);
Route::get('/api/tickets', [TicketsController::class, 'getTicketsByEmail']);
Route::get('/api/sessions/{sessionId}/tickets', [TicketsController::class, 'getSessionTickets']);


Route::post('/sessions/{id}/seats', [FilmSessionsController::class, 'reserveSeats']);








// Rutas de sesiones de cine
Route::get('/sessions', [FilmSessionsController::class, 'index']); 
Route::get('/sessions/{id}', [FilmSessionsController::class, 'show']); 
Route::get('/sessions/{id}/seats', [FilmSessionsController::class, 'seats']); 
Route::post('/sessions', [FilmSessionsController::class, 'store']);

// Rutas de películas
Route::get('/movies', [MoviesController::class, 'index']); 
Route::get('/movies/{id}', [MoviesController::class, 'show']);


// Rutas de tickets
Route::get('/tickets', [TicketsController::class, 'index']); // Obtener todos los tickets
Route::get('/tickets/{id}', [TicketsController::class, 'show']); // Obtener un ticket por ID
Route::post('/tickets', [TicketsController::class, 'store']); // Comprar un ticket
