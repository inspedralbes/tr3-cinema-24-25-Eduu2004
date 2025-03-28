<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\FilmSessionsController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\SeatsController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('movies', MoviesController::class);
Route::resource('sessions', FilmSessionsController::class);
Route::resource('tickets', TicketsController::class);
Route::resource('seats', SeatsController::class);
