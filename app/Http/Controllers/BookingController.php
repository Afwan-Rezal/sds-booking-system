<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use App\Http\Requests\room\BookingRequest;

use App\Mail\BookingPendingApprovalMail;
use App\Mail\BookingUpdatedMail;

use Illuminate\Support\Facades\Mail;
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

        // Auto-complete past bookings for this user
        $this->bookingService->autoCompletePastBookings(Auth::id());

        $bookings = Booking::with(['room.metadata'])
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('lists.booking_list', compact('bookings'));
    }

    public function addBooking(BookingRequest $request)
    {
        
        try {
            // Use the validated data from the BookingRequest FormRequest
            $data = $request->validated();
            // Capture the created Booking so we can access related data (e.g. room name)
            $booking = $this->bookingService->create($data, Auth::id());
            // Ensure the room relation is loaded for the mail payload
            $booking->load('room');
            $user = Auth::user();
            $role = strtolower($user->profile->role ?? '');
            $mailData =[
                // Use the room name from the saved booking when available
                "room" => $booking->room->name ?? ($data['room_id'] ?? 'Room'),
                "date" => $data['date'],
                "time" => $data['time_slot'],
                "name" => $user->profile->full_name,
            ];

            if ($role === 'admin') {
                $message = 'Room booked successfully!';
            } else {
                $message = 'Booking request submitted successfully! It is pending admin approval.';
                Mail::to($user->email)->send(new BookingPendingApprovalMail($mailData));
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
        // Prevent editing completed or rejected bookings
        if ($booking->status === 'completed' || $booking->status === 'rejected') {
            return redirect()->route('bookings.list')
                ->withErrors(['error' => 'Cannot edit ' . $booking->status . ' bookings.']);
        }
        return view('forms.edit_booking', compact('booking'));
    }

    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        // Prevent updating completed or rejected bookings
        if ($booking->status === 'completed' || $booking->status === 'rejected') {
            return redirect()->route('bookings.list')
                ->withErrors(['error' => 'Cannot update ' . $booking->status . ' bookings.']);
        }

        $data = $request->validate([
            'date' => 'required|date',
            'time_slot' => 'required|string',
            'number_of_people' => 'required|integer|min:1',
            'purpose' => 'required|string'
        ]);

        $mailData =[
            "name" => $booking->user->profile['full_name'],
            "date" => $data['date'],
            "time_slot" => Carbon::parse($booking->start_time)->format('H:i') . ' - ' . Carbon::parse($booking->end_time)->format('H:i'),
            "room" => $booking->room->name,
        ];

        try {
            $this->bookingService->update($booking, $data, Auth::id());
            Mail::to($booking->user->email)->send(new BookingUpdatedMail($mailData));
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()->route('bookings.list')
            ->with('success', 'Booking updated successfully!');
    }

    public function deleteBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        // Prevent cancelling completed or rejected bookings
        if ($booking->status === 'completed' || $booking->status === 'rejected') {
            return redirect()->route('bookings.list')
                ->withErrors(['error' => 'Cannot cancel ' . $booking->status . ' bookings.']);
        }

        // We mark the booking as cancelled and store the cancellation reason (if any)
        $reason = $request->input('cancellation_reason');

        $booking->status = 'cancelled';
        if (!empty($reason)) {
            // Option: preserve original purpose and prepend cancellation note
            $booking->purpose = 'Cancelled: ' . $reason . '\n\nPrevious purpose:\n' . ($booking->purpose ?? '');
        }
        $booking->save();

        return redirect()->route('bookings.list')->with('success', 'Booking cancelled successfully!');
    }
}
