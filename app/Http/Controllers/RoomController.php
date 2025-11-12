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
        // Eager-load room metadata and furniture for display
        $rooms = Room::with(['metadata', 'furniture'])->get();

        $result = $this->roomViewBuilder->buildRoomViewData($rooms);

        // Build permission data: check if current user can book each room
        $canBookRoom = [];
        if (Auth::check()) {
            $userRole = Auth::user()->profile ? strtolower(Auth::user()->profile->role) : null;
            foreach ($rooms as $room) {
                $canBookRoom[$room->id] = $this->canUserBookRoom($room, $userRole);
            }
        } else {
            // Guest users cannot book any room
            foreach ($rooms as $room) {
                $canBookRoom[$room->id] = false;
            }
        }

        return view('lists.room_listing', [
            'rooms' => $rooms,
            'roomView' => $result['roomView'],
            'now' => $result['now'],
            'canBookRoom' => $canBookRoom,
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

    /**
     * Check if user with given role can book the specified room.
     *
     * @param Room $room
     * @param string|null $role
     * @return bool
     */
    private function canUserBookRoom(Room $room, ?string $role): bool
    {
        if (!$room->metadata) {
            return false;
        }

        $meta = $room->metadata;

        if ($role === 'admin') {
            return (bool) ($meta->admin_can_book ?? false);
        } elseif ($role === 'staff') {
            return (bool) ($meta->staff_can_book ?? false);
        } elseif ($role === 'student') {
            return (bool) ($meta->student_can_book ?? false);
        }

        return false;
    }
}
