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
        // Validation is now performed by FormRequest (App\Http\Requests\room\BookingRequest)
        // Keep the old validator available in this service for reference, but do not
        // run it here to avoid duplicate validation. If you prefer service-level
        // validation, uncomment the following line.
        // $this->validateInput($data);

        [$start_time, $end_time] = $this->parseTimeSlot($data['time_slot']);

        $now = now();
        $this->ensureFutureWindow($data['date'], $start_time, $now);
        $this->ensureLeadTime($data['date'], $start_time, $now);

        $this->ensureAvailability((int) $data['room_id'], $data['date'], $start_time, $end_time);

        $room = $this->loadRoomWithCapacity((int) $data['room_id']);
        // $this->ensureRoomAllowsRole($room, $this->getUserRole($userId));

        $this->enforceCapacityBounds($room, (int) $data['number_of_people']);

        // Check booking limit before creating new booking
        $this->ensureBookingLimit($userId);

        // Determine status based on user role
        $userRole = $this->getUserRole($userId);
        $status = ($userRole === 'admin') ? 'approved' : 'pending';

        return $this->createBookingRecord($data, $userId, $start_time, $end_time, $status);
    }

    public function update(Booking $booking, array $data, int $userId): Booking
    {
        [$start_time, $end_time] = $this->parseTimeSlot($data['time_slot']);

        $now = now();
        $this->ensureFutureWindow($data['date'], $start_time, $now);
        $this->ensureLeadTime($data['date'], $start_time, $now);

        // Avoid checking against the same booking when verifying availability
        $existingBooking = DB::table('bookings')
            ->where('room_id', $booking->room_id)
            ->where('date', $data['date'])
            ->where('start_time', $start_time)
            ->where('end_time', $end_time)
            ->where('status', 'approved')
            ->where('id', '!=', $booking->id)
            ->first();

        if ($existingBooking) {
            throw ValidationException::withMessages([
                'time_slot' => ['Room is already booked for the selected date and time slot.'],
            ]);
        }

        $room = $this->loadRoomWithCapacity((int) $booking->room_id);
        $this->enforceCapacityBounds($room, (int) $data['number_of_people']);

        $booking->update([
            'date'       => $data['date'],
            'time_slot'  => $data['time_slot'],
            'start_time' => $start_time,
            'end_time'   => $end_time,
            'number_of_people' => $data['number_of_people'],
            'purpose'    => $data['purpose'],
        ]);

        return $booking;
    }


    private function validateInput(array $data): void
    {
        // NOTE: This method contains duplicate validation rules that are now
        // expressed in App\Http\Requests\room\BookingRequest. The method is
        // left here for reference and for potential re-use in non-HTTP contexts
        // (e.g. CLI or background jobs). If you want the service to perform
        // validation again, uncomment the implementation below and the call in
        // create().

        /*
        $validator = Validator::make($data, [
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'number_of_people' => 'required|integer|min:1',
            'purpose' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        */
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
            ->where('status', 'approved') // Only check approved bookings for availability
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

    private function createBookingRecord(array $data, int $userId, string $start_time, string $end_time, string $status): Booking
    {
        return Booking::create([
            'room_id'    => $data['room_id'],
            'user_id'    => $userId,
            'date'       => $data['date'],
            'time_slot'  => $data['time_slot'],
            'start_time' => $start_time,
            'end_time'   => $end_time,
            'status'     => $status,
            'number_of_people' => $data['number_of_people'],
            'purpose'    => $data['purpose'],
        ]);
    }

    /**
     * Automatically mark past approved bookings as completed.
     * 
     * @param int|null $userId If provided, only complete bookings for this user. If null, complete all past bookings.
     * @return void
     */
    public function autoCompletePastBookings(?int $userId = null): void
    {
        $now = Carbon::now();
        
        $query = Booking::where('status', 'approved');

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        $bookings = $query->get();
        
        $pastBookings = $bookings->filter(function ($booking) use ($now) {
            $bookingDateTime = Carbon::parse($booking->date . ' ' . $booking->end_time);
            return $bookingDateTime->lt($now);
        });

        $bookingIds = $pastBookings->pluck('id');
        
        if ($bookingIds->isNotEmpty()) {
            Booking::whereIn('id', $bookingIds)->update(['status' => 'completed']);
        }
    }

    /**
     * Ensure user has not reached the booking limit (3 active bookings).
     * Active bookings are those with status 'approved' or 'pending'.
     * Rejected and completed bookings do not count toward the limit.
     * 
     * @param int $userId
     * @return void
     * @throws ValidationException
     */
    private function ensureBookingLimit(int $userId): void
    {
        // First, auto-complete any past bookings for this user to ensure accurate count
        $this->autoCompletePastBookings($userId);

        // Count active bookings (approved or pending only)
        $activeBookingsCount = Booking::where('user_id', $userId)
            ->whereIn('status', ['approved', 'pending'])
            ->count();

        if ($activeBookingsCount >= 3) {
            throw ValidationException::withMessages([
                'booking_limit' => ['You have reached the maximum limit of 3 active bookings. Please wait for your existing bookings to be completed or cancelled before making a new booking request.'],
            ]);
        }
    }

    /**
     * Get the count of active bookings for a user.
     * Active bookings are those with status 'approved' or 'pending'.
     * 
     * @param int $userId
     * @return int
     */
    public function getActiveBookingsCount(int $userId): int
    {
        // First, auto-complete any past bookings for this user to ensure accurate count
        $this->autoCompletePastBookings($userId);

        return Booking::where('user_id', $userId)
            ->whereIn('status', ['approved', 'pending'])
            ->count();
    }
}


