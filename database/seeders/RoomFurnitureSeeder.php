<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomFurnitureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('room_furniture')->insert([
            // Lecture Rooms (L-001 to L-005) - typically have projectors, whiteboards, chairs, desks
            [
                'room_id' => 1, // SDS L-001
                'furniture_name' => 'Projector',
                'quantity' => 1,
                'description' => 'HD Projector with HDMI input',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 1,
                'furniture_name' => 'Whiteboard',
                'quantity' => 2,
                'description' => 'Wall-mounted whiteboards',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 1,
                'furniture_name' => 'Chairs',
                'quantity' => 50,
                'description' => 'Student chairs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 1,
                'furniture_name' => 'Desks',
                'quantity' => 25,
                'description' => 'Student desks',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // SDS L-002
            [
                'room_id' => 2,
                'furniture_name' => 'Projector',
                'quantity' => 1,
                'description' => 'HD Projector',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 2,
                'furniture_name' => 'Whiteboard',
                'quantity' => 1,
                'description' => 'Wall-mounted whiteboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 2,
                'furniture_name' => 'Chairs',
                'quantity' => 40,
                'description' => 'Student chairs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 2,
                'furniture_name' => 'Desks',
                'quantity' => 20,
                'description' => 'Student desks',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // SDS L-003
            [
                'room_id' => 3,
                'furniture_name' => 'Projector',
                'quantity' => 1,
                'description' => 'HD Projector with wireless capability',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 3,
                'furniture_name' => 'Whiteboard',
                'quantity' => 2,
                'description' => 'Wall-mounted whiteboards',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 3,
                'furniture_name' => 'Chairs',
                'quantity' => 60,
                'description' => 'Student chairs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 3,
                'furniture_name' => 'Desks',
                'quantity' => 30,
                'description' => 'Student desks',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Lab Rooms (L-101 to L-106) - typically have computers, lab equipment
            [
                'room_id' => 6, // SDS L-101
                'furniture_name' => 'Computer',
                'quantity' => 30,
                'description' => 'Desktop computers for students',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 6,
                'furniture_name' => 'Chairs',
                'quantity' => 30,
                'description' => 'Office chairs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 6,
                'furniture_name' => 'Projector',
                'quantity' => 1,
                'description' => 'HD Projector for instructor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 6,
                'furniture_name' => 'Whiteboard',
                'quantity' => 1,
                'description' => 'Wall-mounted whiteboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // SDS L-102
            [
                'room_id' => 7,
                'furniture_name' => 'Computer',
                'quantity' => 25,
                'description' => 'Desktop computers for students',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 7,
                'furniture_name' => 'Chairs',
                'quantity' => 25,
                'description' => 'Office chairs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 7,
                'furniture_name' => 'Projector',
                'quantity' => 1,
                'description' => 'HD Projector',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // SDS L-103
            [
                'room_id' => 8,
                'furniture_name' => 'Computer',
                'quantity' => 20,
                'description' => 'Desktop computers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 8,
                'furniture_name' => 'Chairs',
                'quantity' => 20,
                'description' => 'Office chairs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 8,
                'furniture_name' => 'Whiteboard',
                'quantity' => 1,
                'description' => 'Wall-mounted whiteboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Tutorial Room (T-001)
            [
                'room_id' => 12, // SDS T-001
                'furniture_name' => 'Chairs',
                'quantity' => 20,
                'description' => 'Student chairs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 12,
                'furniture_name' => 'Tables',
                'quantity' => 5,
                'description' => 'Round tables for group work',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_id' => 12,
                'furniture_name' => 'Whiteboard',
                'quantity' => 1,
                'description' => 'Portable whiteboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
