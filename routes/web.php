<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

// Home page with player name input
Route::get('/', [GameController::class, 'index'])->name('home');
Route::post('/players', [GameController::class, 'storePlayers'])->name('store.players');

Route::post('/spin', [GameController::class, 'spin'])->name('spin');
