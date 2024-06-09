<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Add Super Admin Account
        \DB::table('users')->insert([
            'role_id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@email.com',
            'password' => bcrypt('admin123')
        ]);
    }
}
