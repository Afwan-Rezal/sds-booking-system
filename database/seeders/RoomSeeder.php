<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('room_metadatas')->insert([
            [
                'id' => 1,
                'capacity' => 25,
                'type' => 'Computer Lab - Small',
                'location' => 'Ground Floor',
            ],
            
            [
                'id' => 2,
                'capacity' => 50,
                'type' => 'Computer Lab - Large',
                'location' => 'Ground Floor',
            ],

            [
                'id' => 3,
                'capacity' => 15,
                'type' => 'Lecture Room - Small',
                'location' => '1st Floor',
            ],

            [
                'id' => 4,
                'capacity' => 30,
                'type' => 'Lecture Room - Large',
                'location' => '1st Floor',
            ],

            [
                'id' => 5,
                'capacity' => 0,
                'type' => 'Tutorial Room',
                'location' => 'Ground Floor',
            ],
        ]);

        DB::table('rooms')->insert([
            [
                'id' => 1,
                'name' => 'SDS L-001',
                'is_available' => true,
                'room_metadata_id' => 1
            ],
    
            [
                'id' => 2,
                'name' => 'SDS L-002',
                'is_available' => true,
                'room_metadata_id' => 1
            ],
    
            [
                'id' => 3,
                'name' => 'SDS L-003',
                'is_available' => true,
                'room_metadata_id' => 1
            ],

            [
                'id' => 4,
                'name' => 'SDS L-004',
                'is_available' => true,
                'room_metadata_id' => 2
            ],

            [
                'id' => 5,
                'name' => 'SDS L-005',
                'is_available' => true,
                'room_metadata_id' => 2
            ],

            [
                'id' => 6,
                'name' => 'SDS L-101',
                'is_available' => true,
                'room_metadata_id' => 3
            ],

            [
                'id' => 7,
                'name' => 'SDS L-102',
                'is_available' => true,
                'room_metadata_id' => 3
            ],

            [
                'id' => 8,
                'name' => 'SDS L-103',
                'is_available' => true,
                'room_metadata_id' => 3
            ],

            [
                'id' => 9,
                'name' => 'SDS L-104',
                'is_available' => true,
                'room_metadata_id' => 3
            ],

            [
                'id' => 10,
                'name' => 'SDS L-105',
                'is_available' => true,
                'room_metadata_id' => 4
            ],

            [
                'id' => 11,
                'name' => 'SDS L-106',
                'is_available' => true,
                'room_metadata_id' => 4
            ],

            [
                'id' => 12,
                'name' => 'SDS T-001',
                'is_available' => true,
                'room_metadata_id' => 5
            ],
        ]);
    }
}
