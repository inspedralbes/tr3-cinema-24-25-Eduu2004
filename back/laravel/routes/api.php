<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmSessionsController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\AuthController;


Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('/tickets/purchase', [TicketsController::class, 'purchase']);
Route::get('/api/tickets', [TicketsController::class, 'getTicketsByEmail']);
Route::get('/api/sessions/{sessionId}/tickets', [TicketsController::class, 'getSessionTickets']);
Route::post('/sessions/{id}/seats', [FilmSessionsController::class, 'reserveSeats']);
Route::get('/sessions', [FilmSessionsController::class, 'index']); 
Route::get('/movies', [MoviesController::class, 'index']); 

