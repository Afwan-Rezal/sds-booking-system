<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BookingService
{
    /**
     * Validate and create a booking for a user.
     *
     * @param array $data
     * @param int $userId
     * @return Booking
     * @throws ValidationException
     */
    public function create(array $data, int $userId): Booking
    {
        $validator = Validator::make($data, [
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'number_of_people' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        [$start_time, $end_time] = array_map('trim', explode('-', $data['time_slot']));

        $now = now();
        $startDateTime = Carbon::parse(($data['date'] ?? '') . ' ' . $start_time);
        if ($startDateTime->lte($now)) {
            throw ValidationException::withMessages([
                'date' => ['Booking must be made for a future date and/or timeslot.'],
            ]);
        }

        $minutesUntilStart = $now->diffInMinutes($startDateTime);
        if ($minutesUntilStart <= 30) {
            throw ValidationException::withMessages([
                'time_slot' => ['Bookings must be made more than 30 minutes before the start time.'],
            ]);
        }

        $existingBooking = DB::table('bookings')
            ->where('room_id', $data['room_id'])
            ->where('date', $data['date'])
            ->where('start_time', $start_time)
            ->where('end_time', $end_time)
            ->first();

        if ($existingBooking) {
            throw ValidationException::withMessages([
                'time_slot' => ['Room is already booked for the selected date and time slot.'],
            ]);
        }

        $room = Room::with('metadata')->findOrFail($data['room_id']);
        $capacity = optional($room->metadata)->capacity;

        if (is_null($capacity)) {
            throw ValidationException::withMessages([
                'room_id' => ['Capacity for the selected room is not defined.'],
            ]);
        }

        $minimumAllowed = (int) ceil(((int) $capacity) / 2);
        if ((int) $data['number_of_people'] < $minimumAllowed) {
            throw ValidationException::withMessages([
                'number_of_people' => ["Requested number of people must be at least {$minimumAllowed} for this room."],
            ]);
        }

        if ((int) $data['number_of_people'] > (int) $capacity) {
            throw ValidationException::withMessages([
                'number_of_people' => ["Requested number of people exceeds room capacity ({$capacity})."],
            ]);
        }

        return Booking::create([
            'room_id'    => $data['room_id'],
            'user_id'    => $userId,
            'date'       => $data['date'],
            'time_slot'  => $data['time_slot'],
            'start_time' => $start_time,
            'end_time'   => $end_time,
            'status'     => 'approved',
            'number_of_people' => $data['number_of_people'],
        ]);
    }
}


