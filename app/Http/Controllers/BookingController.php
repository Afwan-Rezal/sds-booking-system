<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
        ) {}

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('auth')->with('error', 'You must be logged in to view your bookings.');
        }

        $bookings = Booking::with(['room.metadata'])
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('lists.booking_list', compact('bookings'));
    }

    public function addBooking(Request $request)
    {
        try {
            $this->bookingService->create($request->all(), Auth::id());
            $user = Auth::user();
            $role = strtolower($user->profile->role ?? '');
            if ($role === 'admin') {
                $message = 'Room booked successfully!';
            } else {
                $message = 'Booking request submitted successfully! It is pending admin approval.';
            }
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
        return redirect()->route('rooms.index')->with('success', $message);
    }
}
