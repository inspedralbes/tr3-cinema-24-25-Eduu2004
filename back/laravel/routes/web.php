<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SeatController;

Route::resource('movies', MovieController::class);
Route::resource('sessions', SessionController::class);
Route::resource('tickets', TicketController::class);
Route::resource('seats', SeatController::class);
