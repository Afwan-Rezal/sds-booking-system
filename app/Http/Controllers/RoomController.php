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
            $selectedRoom = Room::findOrFail($roomId);
            return view('forms.room_booking', compact('selectedRoom'));
        }
    }
               
}
