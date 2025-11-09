<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoomController;
use App\Http\Controllers\users\UserController;
use App\Http\Controllers\users\AdminController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('home');
})->middleware('redirect.role');

// Dashboard routes based on role
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth');

Route::get('/staff/dashboard', function () {
    return view('staff.dashboard');
})->name('staff.dashboard')->middleware('auth');

Route::get('/student/dashboard', function () {
    return view('student.dashboard');
})->name('student.dashboard')->middleware('auth');

Route::get('/temporary/dashboard', function () {
    return view('temporary.dashboard');
})->name('temporary.dashboard')->middleware('auth');

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');

Route::get('/rooms/book/{id}', [RoomController::class, 'selectRoom'])->name('rooms.select'); // TODO: Change function name to selectRoom and name to rooms.select
Route::post('/rooms/book/{id}', [BookingController::class, 'addBooking'])->name('rooms.storeBooking');

Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.list')->middleware('auth');

Route::get('/booking/edit/{id}', [BookingController::class, 'selectBooking'])->name('bookings.edit')->middleware('auth');
Route::post('/booking/edit/{id}', [BookingController::class, 'updateBooking'])->name('bookings.update')->middleware('auth');
Route::post('/booking/delete/{id}', [BookingController::class, 'deleteBooking'])->name('bookings.delete')->middleware('auth');

Route::controller(UserController::class)->group(function () {
    Route::get('login', [UserController::class, 'index'])->name('auth');
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout');
});

// Admin management routes
Route::middleware('auth')->group(function () {
    Route::get('/admin/users', [AdminController::class, 'showUsers'])->name('admin.users');
    Route::get('/admin/pending-staff', [AdminController::class, 'showPendingStaff'])->name('admin.pending_staff');
    Route::post('/admin/approve-staff/{user}', [AdminController::class, 'approveStaff'])->name('admin.approve_staff');
    Route::post('/admin/reject-staff/{user}', [AdminController::class, 'rejectStaff'])->name('admin.reject_staff');
    
    // Booking approval routes
    Route::get('/admin/pending-bookings', [AdminController::class, 'showPendingBookings'])->name('admin.pending_bookings');
    Route::post('/admin/approve-booking/{booking}', [AdminController::class, 'approveBooking'])->name('admin.approve_booking');
    Route::post('/admin/reject-booking/{booking}', [AdminController::class, 'rejectBooking'])->name('admin.reject_booking');
});
