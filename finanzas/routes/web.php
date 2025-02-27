<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\EntriesController;
use App\Http\Controllers\ExpensesController;

use App\Http\Controllers\HistoriesController;

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
    Route::post('/Ingresos', action: [EntriesController::class, 'store'])->name('entries.store');
    Route::put('/Ingresos/{id}', [EntriesController::class, 'update'])->name('entries.update');
    Route::delete('/Ingresos/{id}', [EntriesController::class, 'destroy'])->name('entries.destroy');
    Route::get('/Salidas', [ExpensesController::class, 'index'])->name('expenses.index');
    Route::post('/Salidas', action: [ExpensesController::class, 'store'])->name('expenses.store');
    Route::put('/Salidas/{id}', [ExpensesController::class, 'update'])->name('expenses.update');
    Route::delete('/Salidas/{id}', [ExpensesController::class, 'destroy'])->name('expenses.destroy');
    Route::resource('histories', HistoriesController::class)->only(['index']);
    Route::get('/histories/details/{month}/{year}', [HistoriesController::class, 'getDetails']);

});