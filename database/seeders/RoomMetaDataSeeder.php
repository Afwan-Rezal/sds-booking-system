<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomMetaDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('room_metadatas')->insert([
            // Computer Lab - Small (rooms 1-3)
            // Admin: YES, Staff: YES, Student: NO
            [
                'id' => 1,
                'capacity' => 25,
                'type' => 'Computer Lab - Small',
                'location' => 'Ground Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 1,
                'student_can_book' => 0,
            ],

            [
                'id' => 2,
                'capacity' => 25,
                'type' => 'Computer Lab - Small',
                'location' => 'Ground Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 1,
                'student_can_book' => 0,
            ],

            [
                'id' => 3,
                'capacity' => 25,
                'type' => 'Computer Lab - Small',
                'location' => 'Ground Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 1,
                'student_can_book' => 0,
            ],
            
            // Computer Lab - Large (rooms 4-5)
            // Admin: YES, Staff: NO, Student: NO
            [
                'id' => 4,
                'capacity' => 50,
                'type' => 'Computer Lab - Large',
                'location' => 'Ground Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 0,
                'student_can_book' => 0,
            ],

            [
                'id' => 5,
                'capacity' => 50,
                'type' => 'Computer Lab - Large',
                'location' => 'Ground Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 0,
                'student_can_book' => 0,
            ],

            // Lecture Room - Small (rooms 6-9)
            // Admin: YES, Staff: YES, Student: YES
            [
                'id' => 6,
                'capacity' => 15,
                'type' => 'Lecture Room - Small',
                'location' => '1st Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 1,
                'student_can_book' => 1,
            ],

            [
                'id' => 7,
                'capacity' => 15,
                'type' => 'Lecture Room - Small',
                'location' => '1st Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 1,
                'student_can_book' => 1,
            ],

            [
                'id' => 8,
                'capacity' => 15,
                'type' => 'Lecture Room - Small',
                'location' => '1st Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 1,
                'student_can_book' => 1,
            ],

            [
                'id' => 9,
                'capacity' => 15,
                'type' => 'Lecture Room - Small',
                'location' => '1st Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 1,
                'student_can_book' => 1,
            ],

            // Lecture Room - Large (rooms 10-11)
            // Admin: YES, Staff: YES, Student: NO
            [
                'id' => 10,
                'capacity' => 30,
                'type' => 'Lecture Room - Large',
                'location' => '1st Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 1,
                'student_can_book' => 0,
            ],

            [
                'id' => 11,
                'capacity' => 30,
                'type' => 'Lecture Room - Large',
                'location' => '1st Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 1,
                'student_can_book' => 0,
            ],

            // Tutorial Room (room 12)
            // Admin: YES, Staff: YES, Student: YES
            [
                'id' => 12,
                'capacity' => 15,
                'type' => 'Tutorial Room',
                'location' => 'Ground Floor',
                'admin_can_book' => 1,
                'staff_can_book' => 1,
                'student_can_book' => 1,
            ],
        ]);
    }
}
