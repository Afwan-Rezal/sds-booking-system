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
                'email' => 'afwanrezal.dev@gmail.com',
                'password' => bcrypt('AsgPassword_Admin01'), 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'username' => 'staff',
                'email' => 'staff@mail.com',
                'password' => bcrypt('AsgPassword_Staff02'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'username' => '21B6027',
                'email' => 'afwan.rezal@gmail.com',
                'password' => bcrypt('AsgPassword_User03'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('user_profiles')->insert([
            [
                'user_id' => 1,
                'full_name' => 'Afwan Rezal Admin',
                'role' => 'admin',
                'gender' => 'M',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'user_id' => 2,
                'full_name' => 'Ahmad Staff',
                'role' => 'staff',
                'gender' => 'M',
                'created_at' => now(),
                'updated_at' => now(),
            ],  
            
            [
                'user_id' => 3,
                'full_name' => 'Afif Afwan Bin Mohamad Rezal',
                'role' => 'student',
                'gender' => 'M',
                'created_at' => now(),
                'updated_at' => now(),
            ],  
        ]);
    }
}
