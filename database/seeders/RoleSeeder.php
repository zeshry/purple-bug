<?php

namespace Database\Seeders;

use App\Models\Role; // Make sure the correct model is imported
use App\Models\Roles;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roles::updateOrCreate(
            ['role_name' => 'Admin'],
            [
                'description' => 'Master Access'
            ]
        );

        Roles::updateOrCreate(
            ['role_name' => 'User'],
            [
                'description' => 'Basic Access'
            ]
        );
    }
}
