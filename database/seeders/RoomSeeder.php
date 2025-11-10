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
        DB::table('rooms')->insert([
            [
                'id' => 1,
                'name' => 'SDS L-001',
                'room_metadata_id' => 1
            ],

            [
                'id' => 2,
                'name' => 'SDS L-002',
                'room_metadata_id' => 2
            ],

            [
                'id' => 3,
                'name' => 'SDS L-003',
                'room_metadata_id' => 3
            ],

            [
                'id' => 4,
                'name' => 'SDS L-004',
                'room_metadata_id' => 4
            ],

            [
                'id' => 5,
                'name' => 'SDS L-005',
                'room_metadata_id' => 5
            ],

            [
                'id' => 6,
                'name' => 'SDS L-101',
                'room_metadata_id' => 6
            ],

            [
                'id' => 7,
                'name' => 'SDS L-102',
                'room_metadata_id' => 7
            ],

            [
                'id' => 8,
                'name' => 'SDS L-103',
                'room_metadata_id' => 8
            ],

            [
                'id' => 9,
                'name' => 'SDS L-104',
                'room_metadata_id' => 9
            ],

            [
                'id' => 10,
                'name' => 'SDS L-105',
                'room_metadata_id' => 10
            ],

            [
                'id' => 11,
                'name' => 'SDS L-106',
                'room_metadata_id' => 11
            ],

            [
                'id' => 12,
                'name' => 'SDS T-001',
                'room_metadata_id' => 12
            ],
        ]);
    }
}
