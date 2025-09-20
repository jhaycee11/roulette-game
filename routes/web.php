<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AdminController;

// Home page with player name input
Route::get('/', [GameController::class, 'index'])->name('home');
Route::post('/players', [GameController::class, 'storePlayers'])->name('store.players');

Route::post('/spin', [GameController::class, 'spin'])->name('spin');

// Admin section
Route::get('/admin', [AdminController::class, 'index'])->name('admin');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Protected admin routes
Route::middleware(['admin'])->group(function () {
    Route::post('/admin/add-win', [AdminController::class, 'addWin'])->name('admin.add.win');
    Route::delete('/admin/next-to-win/clear', [AdminController::class, 'clearNextToWin'])->name('admin.clear.next.to.win');
});

// Debug route (accessible from home page)
Route::post('/debug/next-to-win', [AdminController::class, 'debugNextToWin'])->name('admin.debug.next.to.win');
