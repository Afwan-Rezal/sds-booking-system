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

    public function selectBooking($id)
    {
        $booking = Booking::with(['room.metadata'])->findOrFail($id);
        // Only allow user to edit their own booking (unless admin/staff)
        if (Auth::id() !== $booking->user_id) {
            abort(403); // Forbidden
        }
        return view('forms.edit_booking', compact('booking'));
    }

    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }
        $data = $request->validate([
            'date' => 'required|date',
            'time_slot' => 'required',
            'number_of_people' => 'required|integer|min=1',
            'purpose' => 'required|string'
        ]);
        $booking->update($data);
        return redirect()->route('bookings.list')->with('success', 'Booking updated successfully!');
    }

    public function deleteBooking($id)
    {
        $booking = Booking::findOrFail($id);
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }
        $booking->delete();
        return redirect()->route('bookings.list')->with('success', 'Booking deleted successfully!');
    }
}
