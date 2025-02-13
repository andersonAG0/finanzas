<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chat;
use App\Http\Controllers\Summary;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::middleware('auth:sanctum')->group(callback: function () {
    Route::get('/dashboard', [Summary::class, 'index'])->name('dashboard');
    Route::get('/chat', [Chat::class, 'index'])->name('chat');
    Route::post('/chat/send', [Chat::class, 'sendMessage'])->name('chat.send');
});