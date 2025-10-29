<?php

namespace App\Services;

use App\Models\User;
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
        $this->validateInput($data);

        [$start_time, $end_time] = $this->parseTimeSlot($data['time_slot']);

        $now = now();
        $this->ensureFutureWindow($data['date'], $start_time, $now);
        $this->ensureLeadTime($data['date'], $start_time, $now);

        $this->ensureAvailability((int) $data['room_id'], $data['date'], $start_time, $end_time);

        $room = $this->loadRoomWithCapacity((int) $data['room_id']);
        // $this->ensureRoomAllowsRole($room, $this->getUserRole($userId));

        $this->enforceCapacityBounds($room, (int) $data['number_of_people']);

        return $this->createBookingRecord($data, $userId, $start_time, $end_time);
    }

    private function validateInput(array $data): void
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
    }

    private function parseTimeSlot(string $timeSlot): array
    {
        [$start_time, $end_time] = array_map('trim', explode('-', $timeSlot));
        return [$start_time, $end_time];
    }

    private function ensureFutureWindow(string $date, string $start_time, Carbon $now): void
    {
        $startDateTime = Carbon::parse($date . ' ' . $start_time);
        if ($startDateTime->lte($now)) {
            throw ValidationException::withMessages([
                'date' => ['Booking must be made for a future date and/or timeslot.'],
            ]);
        }
    }

    private function ensureLeadTime(string $date, string $start_time, Carbon $now): void
    {
        $startDateTime = Carbon::parse($date . ' ' . $start_time);
        $minutesUntilStart = $now->diffInMinutes($startDateTime);
        if ($minutesUntilStart <= 30) {
            throw ValidationException::withMessages([
                'time_slot' => ['Bookings must be made more than 30 minutes before the start time.'],
            ]);
        }
    }

    private function ensureAvailability(int $roomId, string $date, string $start_time, string $end_time): void
    {
        $existingBooking = DB::table('bookings')
            ->where('room_id', $roomId)
            ->where('date', $date)
            ->where('start_time', $start_time)
            ->where('end_time', $end_time)
            ->first();

        if ($existingBooking) {
            throw ValidationException::withMessages([
                'time_slot' => ['Room is already booked for the selected date and time slot.'],
            ]);
        }
    }

    private function loadRoomWithCapacity(int $roomId): Room
    {
        $room = Room::with('metadata')->findOrFail($roomId);
        $capacity = optional($room->metadata)->capacity;

        if (is_null($capacity)) {
            throw ValidationException::withMessages([
                'room_id' => ['Capacity for the selected room is not defined.'],
            ]);
        }

        return $room;
    }

    private function enforceCapacityBounds(Room $room, int $requestedCount): void
    {
        $capacity = (int) optional($room->metadata)->capacity;
        $minimumAllowed = (int) ceil($capacity / 2);

        if ($requestedCount < $minimumAllowed) {
            throw ValidationException::withMessages([
                'number_of_people' => ["Requested number of people must be at least {$minimumAllowed} for this room."],
            ]);
        }

        if ($requestedCount > $capacity) {
            throw ValidationException::withMessages([
                'number_of_people' => ["Requested number of people exceeds room capacity ({$capacity})."],
            ]);
        }
    }

    private function getUserRole(int $userId): ?string
    {
        $user = User::with('profile')->findOrFail($userId);
        return $user->profile ? strtolower($user->profile->role) : null;
    }

    private function ensureRoomAllowsRole(Room $room, ?string $role): void
    {
        $meta = $room->metadata;

        $allowed = false;

        if ($role === 'admin') {
            $allowed = (bool) ($meta->admin_can_book ?? false);
        } elseif ($role === 'staff') {
            $allowed = (bool) ($meta->staff_can_book ?? false);
        } elseif ($role === 'student') {
            $allowed = (bool) ($meta->student_can_book ?? false);
        } else {
            $allowed = false;
        }

        if (!$allowed) {
            throw ValidationException::withMessages([
                'room_id' => ['Your role is not permitted to book this room.'],
            ]);
        }
    }

    private function createBookingRecord(array $data, int $userId, string $start_time, string $end_time): Booking
    {
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


