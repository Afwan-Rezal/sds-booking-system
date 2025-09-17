<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomsController;

Route::get('/', function () {
    return view('home');
});

Route::get('rooms', [RoomsController::class, 'create']);
