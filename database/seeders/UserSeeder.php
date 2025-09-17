<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('12345678'), 
            ],
            [
                'id' => 2,
                'username' => 'staff',
                'email' => 'staff@mail.com',
                'password' => bcrypt('12345678'),
            ],
            [
                'id' => 3,
                'username' => '21B6027',
                'email' => '21B6027@ubd.edu.bn',
                'password' => bcrypt('12345678'),
            ],
        ]);

        DB::table('user_profiles')->insert([
            [
                'user_id' => 3,
                'full_name' => 'Afif Afwan Bin Mohamad Rezal',
                'role' => 'Student',
                'gender' => 'M',
                'created_at' => now(),
                'updated_at' => now(),
            ],  
        ]);
    }
}
