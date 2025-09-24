<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CustomWinnerController;

// Home page with player name input
Route::get('/', [GameController::class, 'index'])->name('home');
Route::post('/players', [GameController::class, 'storePlayers'])->name('store.players');

Route::post('/spin', [GameController::class, 'spin'])->name('spin')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Custom Winner Management
Route::get('/custom-winner', [CustomWinnerController::class, 'index'])->name('custom-winner.index');
Route::post('/custom-winner', [CustomWinnerController::class, 'update'])->name('custom-winner.update');
Route::get('/custom-winner/clear', [CustomWinnerController::class, 'clear'])->name('custom-winner.clear');

// API endpoint for custom winner data
Route::get('/api/custom-winner', [CustomWinnerController::class, 'getCustomWinner'])->name('api.custom-winner');
Route::post('/api/custom-winner/clear', [CustomWinnerController::class, 'clearCustomWinner'])->name('api.custom-winner.clear')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Cache clearing route for deployment
Route::get('/clear-all', function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return '✅ All caches cleared!';
});

