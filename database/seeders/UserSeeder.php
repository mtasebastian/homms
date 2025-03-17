<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Add Super Admin Account
        User::insert([
            'role_id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@email.com',
            'password' => bcrypt('admin123'),
            'mobileno' => '09123456789',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
