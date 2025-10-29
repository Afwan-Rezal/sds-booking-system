<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\RoomViewBuilder;
use App\Services\BookingService;
use Illuminate\Validation\ValidationException;

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

        return view('room_listing', [
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
            $selectedRoom = Room::findOrFail($roomId);
            return view('room_booking', compact('selectedRoom'));
        }
    }

    public function storeBooking(Request $request)
    {
        try {
            $this->bookingService->create($request->all(), Auth::id());
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('rooms.index')->with('success', 'Room booked successfully!');
    }
               
}
