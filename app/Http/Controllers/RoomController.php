<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Services\RoomViewBuilder;
use App\Services\BookingService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Carbon\Carbon;

class RoomController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private RoomViewBuilder $roomViewBuilder
    ) {}

    public function index()
    {
        // Eager-load room metadata for display
        $rooms = Room::with('metadata')->get();

        $result = $this->roomViewBuilder->buildRoomViewData($rooms);

        return view('lists.room_listing', [
            'rooms' => $rooms,
            'roomView' => $result['roomView'],
            'now' => $result['now'],
        ]);
    }

    public function selectRoom($roomId)
    {
        if (!Auth::check()) 
        {
            return redirect()->route('auth')->with('error', 'You must be logged in to book a room.');
        } else
        {
            // Check if user has reached booking limit
            $activeBookingsCount = $this->bookingService->getActiveBookingsCount(Auth::id());
            
            if ($activeBookingsCount >= 3) { // Hardcoded booking limit for now
                return redirect()->route('rooms.index')
                    ->withErrors(['booking_limit' => 'You have reached the maximum limit of 3 active bookings. Please wait for your existing bookings to be completed or cancelled before making a new booking request.']);
            }
            
            $selectedRoom = Room::with('metadata')->findOrFail($roomId);

            if ($selectedRoom->metadata && $selectedRoom->metadata->is_blocked) {
                $reason = $selectedRoom->metadata->blocked_reason
                    ? ' Reason: ' . $selectedRoom->metadata->blocked_reason
                    : '';

                return redirect()->route('rooms.index')
                    ->withErrors(['room_blocked' => 'This room is currently blocked and cannot be booked.' . $reason]);
            }

            // The 'is_available' column has been removed. Availability is
            // determined by room metadata (blocked state). If you require a
            // separate maintenance flag, add it to RoomMetadata and check it
            // here.

            return view('forms.room_booking', compact('selectedRoom'));
        }
    }
               
}
