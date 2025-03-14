<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmSessionsController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\SeatsController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\AuthController;


Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);


Route::post('/sessions/{id}/seats', [FilmSessionsController::class, 'reserveSeats']);

// Rutas de sesiones de cine
Route::get('/sessions', [FilmSessionsController::class, 'index']); // Obtener todas las sesiones
Route::get('/sessions/upcoming', [FilmSessionsController::class, 'upcoming']); // Próximas sesiones (si agregas esta función)
Route::get('/sessions/{id}', [FilmSessionsController::class, 'show']); // Obtener una sesión por ID
Route::get('/sessions/{id}/seats', [FilmSessionsController::class, 'seats']); // Obtener asientos de una sesión
Route::post('/sessions', [FilmSessionsController::class, 'store']); // Crear una sesión
Route::put('/sessions/{id}', [FilmSessionsController::class, 'update']); // Actualizar una sesión
Route::delete('/sessions/{id}', [FilmSessionsController::class, 'destroy']); // Eliminar una sesión

// Rutas de películas
Route::get('/movies', [MoviesController::class, 'index']); // Obtener todas las películas
Route::get('/movies/{id}', [MoviesController::class, 'show']); // Obtener una película por ID
Route::post('/movies', [MoviesController::class, 'store']); // Agregar una película
Route::put('/movies/{id}', [MoviesController::class, 'update']); // Actualizar una película
Route::get('/movies/{id}', [MoviesController::class, 'show']);
Route::delete('/movies/{id}', [MoviesController::class, 'destroy']); // Eliminar una película

// Rutas de asientos
Route::get('/seats', [SeatsController::class, 'index']); // Obtener todos los asientos
Route::get('/seats/{id}', [SeatsController::class, 'show']); // Obtener un asiento por ID
Route::post('/seats', [SeatsController::class, 'store']); // Crear un asiento
Route::put('/seats/{id}', [SeatsController::class, 'update']); // Actualizar un asiento
Route::delete('/seats/{id}', [SeatsController::class, 'destroy']); // Eliminar un asiento

// Rutas de tickets
Route::get('/tickets', [TicketsController::class, 'index']); // Obtener todos los tickets
Route::get('/tickets/{id}', [TicketsController::class, 'show']); // Obtener un ticket por ID
Route::post('/tickets', [TicketsController::class, 'store']); // Comprar un ticket
Route::put('/tickets/{id}', [TicketsController::class, 'update']); // Actualizar un ticket
Route::delete('/tickets/{id}', [TicketsController::class, 'destroy']); // Eliminar un ticket
