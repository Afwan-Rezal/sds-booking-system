<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room; // Make sure to import your Room model
use App\Models\Booking; // Import the Booking model
use Illuminate\Support\Facades\Auth;

class RoomsController extends Controller
{
    public function index()
    {
        $rooms = Room::all(); // Fetch all rooms from the database
        return view('room_listing', compact('rooms')); // Pass to the view
    }

    public function bookRoom($roomId)
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

        // Extract start and end time from the slot
        [$start_time, $end_time] = explode('-', $request->time_slot);

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

        // Create the booking
        Booking::create([
            'room_id'    => $request->room_id,
            'user_id'    => Auth::id(),
            'date'       => $request->date,
            'time_slot'  => $request->time_slot,
            'start_time' => $start_time,
            'end_time'   => $end_time,
            'status'     => 'booked',
            'number_of_people' => $request->number_of_people,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room booked successfully!');
    }
               
}
