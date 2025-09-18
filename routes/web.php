<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\users\UserController;

Route::get('/', function () {
    return view('home');
});

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');

Route::get('/rooms/book/{id}', [RoomController::class, 'selectRoom'])->name('rooms.select'); // TODO: Change function name to selectRoom and name to rooms.select
Route::post('/rooms/book/{id}', [RoomController::class, 'storeBooking'])->name('rooms.storeBooking');

Route::controller(UserController::class)->group(function () {
    Route::get('login', [UserController::class, 'index'])->name('auth');
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout');
});
