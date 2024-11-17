<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin Account',
                'password' => bcrypt('12345678'),
                'role' => 'Admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@mail.com'],
            [
                'name' => 'User Account',
                'password' => bcrypt('12345678'),
                'role' => 'User'
            ]
        );
    }
}
