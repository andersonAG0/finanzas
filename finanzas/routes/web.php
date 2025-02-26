<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\EntriesController;

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::middleware('auth:sanctum')->group(callback: function () {
    Route::get('/', [SummaryController::class, 'index'])->name('dashboard');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/Ingresos', [EntriesController::class, 'index'])->name('entries.index');
    Route::post('/Ingresos', [EntriesController::class, 'store'])->name('entries.store');
    Route::delete('/Ingresos', [EntriesController::class, 'destroy'])->name('entries.destroy');
});

