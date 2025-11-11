<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed date range: late October to Nov 10, 2025
        $startDate = Carbon::create(2025, 10, 25); // Oct 25
        $endDate = Carbon::create(2025, 11, 10);   // Nov 10

        // User IDs and Room IDs based on seeders
        $userIds = [2, 3]; // staff and student (skip admin for realism)
        $roomIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        // Booking statuses
        $statuses = ['pending', 'approved', 'rejected'];

        // Sample purposes
        $purposes = [
            'Project meeting',
            'Study group',
            'Lab session',
            'Tutorial class',
            'Presentation prep',
            'Exam preparation',
            'Team collaboration',
            'Research discussion',
            'Group assignment',
            'Lecture review',
        ];

        // Define time slots for the day
        $timeSlots = [
            ['start' => '07:50', 'end' => '09:40'],
            ['start' => '09:50', 'end' => '11:40'],
            ['start' => '11:50', 'end' => '13:40'],
            ['start' => '14:10', 'end' => '16:00'],
        ];

        $bookings = [];
        $now = Carbon::now();

        // Generate bookings for the date range
        for ($date = $startDate->clone(); $date->lte($endDate); $date->addDay()) {
            // Skip weekends (Saturday=6, Sunday=0)
            if ($date->isWeekend()) {
                continue;
            }

            // Generate bookings for each time slot (with some probability of being filled)
            foreach ($timeSlots as $slot) {
                // 70% chance that this slot gets a booking
                if (rand(1, 100) <= 70) {
                    $startTime = $slot['start'] . ':00';
                    $endTime = $slot['end'] . ':00';

                    // Randomly assign status (70% approved, 20% pending, 10% rejected)
                    $rand = rand(1, 100);
                    if ($rand <= 70) {
                        $status = 'approved';
                    } elseif ($rand <= 90) {
                        $status = 'pending';
                    } else {
                        $status = 'rejected';
                    }

                    $bookings[] = [
                        'user_id' => $userIds[array_rand($userIds)],
                        'room_id' => $roomIds[array_rand($roomIds)],
                        'date' => $date->format('Y-m-d'),
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'status' => $status,
                        'number_of_people' => rand(2, 15),
                        'purpose' => $purposes[array_rand($purposes)],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        // Insert bookings in batches
        DB::table('bookings')->insert($bookings);
    }
}
