<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\users\UserController;

Route::get('/', function () {
    return view('home');
});

Route::get('rooms', [RoomsController::class, 'create']);
Route::get('users', [UserController::class, 'index']);

Route::controller(UserController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
});
