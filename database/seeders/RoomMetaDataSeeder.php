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
            [
                'id' => 1,
                'capacity' => 25,
                'type' => 'Computer Lab - Small',
                'location' => 'Ground Floor',
            ],

            [
                'id' => 2,
                'capacity' => 25,
                'type' => 'Computer Lab - Small',
                'location' => 'Ground Floor',
            ],

            [
                'id' => 3,
                'capacity' => 25,
                'type' => 'Computer Lab - Small',
                'location' => 'Ground Floor',
            ],
            
            [
                'id' => 4,
                'capacity' => 50,
                'type' => 'Computer Lab - Large',
                'location' => 'Ground Floor',
            ],

            [
                'id' => 5,
                'capacity' => 50,
                'type' => 'Computer Lab - Large',
                'location' => 'Ground Floor',
            ],

            [
                'id' => 6,
                'capacity' => 15,
                'type' => 'Lecture Room - Small',
                'location' => '1st Floor',
            ],

            [
                'id' => 7,
                'capacity' => 15,
                'type' => 'Lecture Room - Small',
                'location' => '1st Floor',
            ],

            [
                'id' => 8,
                'capacity' => 15,
                'type' => 'Lecture Room - Small',
                'location' => '1st Floor',
            ],

            [
                'id' => 9,
                'capacity' => 15,
                'type' => 'Lecture Room - Small',
                'location' => '1st Floor',
            ],

            [
                'id' => 10,
                'capacity' => 30,
                'type' => 'Lecture Room - Large',
                'location' => '1st Floor',
            ],

            [
                'id' => 11,
                'capacity' => 30,
                'type' => 'Lecture Room - Large',
                'location' => '1st Floor',
            ],

            [
                'id' => 12,
                'capacity' => 15,
                'type' => 'Tutorial Room',
                'location' => 'Ground Floor',
            ],
        ]);
    }
}
