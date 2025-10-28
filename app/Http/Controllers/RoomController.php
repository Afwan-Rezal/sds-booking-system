<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index()
    {
        // Eager-load room metadata for display
        $rooms = Room::with('metadata')->get();

        // Determine current time window and find ongoing bookings per room for today
        $now = now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $currentBookings = Booking::with(['user.profile'])
            ->whereDate('date', $today)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>', $currentTime)
            ->whereIn('room_id', $rooms->pluck('id'))
            ->get()
            ->keyBy('room_id');

        // Build per-room display data for the view
        $roomView = [];
        foreach ($rooms as $room) {
            $current = $currentBookings->get($room->id);
            // Determine threshold for next slot: end of current booking if exists, else now
            $thresholdDate = $today;
            $thresholdTime = $currentTime;
            if ($current) {
                $thresholdDate = $today; // current booking is for today by definition
                $thresholdTime = $current->end_time; // next slot begins at or after current end
            }

            // Find the immediate next time slot booking: earliest with
            // (date == thresholdDate and start_time >= thresholdTime) OR a future date
            $next = Booking::with(['user.profile'])
                ->where('room_id', $room->id)
                ->where(function ($q) use ($thresholdDate, $thresholdTime) {
                    $q->whereDate('date', '>', $thresholdDate)
                      ->orWhere(function ($q2) use ($thresholdDate, $thresholdTime) {
                          $q2->whereDate('date', $thresholdDate)
                             ->where('start_time', '>=', $thresholdTime);
                      });
                })
                ->orderBy('date')
                ->orderBy('start_time')
                ->first();
            if ($current) {
                $user = $current->user;
                $bookerName = optional(optional($user)->profile)->full_name
                    ?? optional($user)->username
                    ?? optional($user)->email
                    ?? 'Unknown user';
                $roomView[$room->id] = [
                    'has_current' => true,
                    'booker_name' => $bookerName,
                    'start_time' => Carbon::parse($current->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($current->end_time)->format('H:i'),
                    'as_of' => $now->format('Y-m-d H:i'),
                    'has_next' => (bool) $next,
                    'next' => $next ? [
                        'date' => Carbon::parse($next->date)->format('Y-m-d'),
                        'start_time' => Carbon::parse($next->start_time)->format('H:i'),
                        'end_time' => Carbon::parse($next->end_time)->format('H:i'),
                        'booker_name' => (optional(optional($next->user)->profile)->full_name
                            ?? optional($next->user)->username
                            ?? optional($next->user)->email
                            ?? 'Unknown user'),
                    ] : null,
                ];
            } else {
                $roomView[$room->id] = [
                    'has_current' => false,
                    'booker_name' => null,
                    'start_time' => null,
                    'end_time' => null,
                    'as_of' => $now->format('Y-m-d H:i'),
                    'has_next' => (bool) $next,
                    'next' => $next ? [
                        'date' => Carbon::parse($next->date)->format('Y-m-d'),
                        'start_time' => Carbon::parse($next->start_time)->format('H:i'),
                        'end_time' => Carbon::parse($next->end_time)->format('H:i'),
                        'booker_name' => (optional(optional($next->user)->profile)->full_name
                            ?? optional($next->user)->username
                            ?? optional($next->user)->email
                            ?? 'Unknown user'),
                    ] : null,
                ];
            }
        }

        return view('room_listing', [
            'rooms' => $rooms,
            'roomView' => $roomView,
            'now' => $now,
        ]);
    }

    public function selectRoom($roomId) // TODO: Change function name to selectRoom
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
