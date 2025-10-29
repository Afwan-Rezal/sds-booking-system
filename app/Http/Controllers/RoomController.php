<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\RoomViewBuilder;

class RoomController extends Controller
{
    public function index()
    {
        // Eager-load room metadata for display
        $rooms = Room::with('metadata')->get();

        $builder = new RoomViewBuilder();
        $result = $builder->buildRoomViewData($rooms);

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
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'number_of_people' => 'required|integer|min:1',
        ]);

        [$start_time, $end_time] = explode('-', $request->time_slot);
        // normalize times
        $start_time = trim($start_time);
        $end_time = trim($end_time);

        // Reject bookings that would start in the past relative to system time
        // e.g. user picks today but a start_time earlier than now
        $now = now();
        $startDateTime = Carbon::parse($request->date . ' ' . $start_time);
        if ($startDateTime->lte($now)) {
            return back()->withErrors(['Booking must be made for a future date and/or timeslot.'])->withInput();
        }

        // Reject bookings made too close to the start time: must be > 30 minutes before start
        $minutesUntilStart = $now->diffInMinutes($startDateTime);
        if ($minutesUntilStart <= 30) {
            return back()->withErrors(['Bookings must be made more than 30 minutes before the start time.'])->withInput();
        }

        // Check if the room is available for the selected date and time slot
        $existingBooking = DB::table('bookings')
            ->where('room_id', $request->room_id)
            ->where('date', $request->date)
            ->where('start_time', $start_time)
            ->where('end_time', $end_time)
            ->first();

        if ($existingBooking) {
            return back()->withErrors(['Room is already booked for the selected date and time slot.'])->withInput();
        }

        // Load room and its capacity from room_metadata, then compare with requested number_of_people
        $room = Room::with('metadata')->findOrFail($request->room_id);
        $capacity = optional($room->metadata)->capacity;

        if (is_null($capacity)) {
            return back()->withErrors(['Capacity for the selected room is not defined.'])->withInput();
        }

        // Reject if requested number is less than half of capacity (rounded up)
        $minimumAllowed = (int) ceil(((int) $capacity) / 2);
        if ((int) $request->number_of_people < $minimumAllowed) {
            return back()->withErrors(["Requested number of people must be at least {$minimumAllowed} for this room."])->withInput();
        }

        if ((int) $request->number_of_people > (int) $capacity) {
            return back()->withErrors(["Requested number of people exceeds room capacity ({$capacity})."])->withInput();
        }
        
        Booking::create([
            'room_id'    => $request->room_id,
            'user_id'    => Auth::id(),
            'date'       => $request->date,
            'time_slot'  => $request->time_slot,
            'start_time' => $start_time,
            'end_time'   => $end_time,
            'status'     => 'approved',
            'number_of_people' => $request->number_of_people,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room booked successfully!');
    }
               
}
