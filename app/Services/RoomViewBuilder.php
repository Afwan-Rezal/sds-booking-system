<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class RoomViewBuilder
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function buildRoomViewData($rooms): array
    {
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

        $roomView = [];
        foreach ($rooms as $room) {
            $current = $currentBookings->get($room->id);

            $thresholdDate = $today;
            $thresholdTime = $currentTime;
            if ($current) {
                $thresholdDate = $today;
                $thresholdTime = $current->end_time;
            }

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

        return [
            'roomView' => $roomView,
            'now' => $now,
        ];
    }
}
