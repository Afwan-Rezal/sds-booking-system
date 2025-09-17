<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\users\UserController;

Route::get('/', function () {
    return view('home');
});

Route::get('/rooms', [RoomsController::class, 'index'])->name('rooms.index');

Route::get('/rooms/book/{id}', [RoomsController::class, 'bookRoom'])->name('rooms.book');
Route::post('/rooms/book/{id}', [RoomsController::class, 'storeBooking'])->name('rooms.storeBooking');

Route::controller(UserController::class)->group(function () {
    Route::get('login', [UserController::class, 'index'])->name('auth');
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout');
});
