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
                'type' => 'Computer Lab',
                'location' => 'Ground Floor',
            ],
            
            [
                'id' => 2,
                'capacity' => 25,
                'type' => 'Computer Lab',
                'location' => 'Ground Floor',
            ],

            [
                'id' => 3,
                'capacity' => 25,
                'type' => 'Computer Lab',
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
                'room_metadata_id' => 2
            ],
    
            [
                'id' => 3,
                'name' => 'SDS L-003',
                'is_available' => true,
                'room_metadata_id' => 3
            ],
        ]);
    }
}
