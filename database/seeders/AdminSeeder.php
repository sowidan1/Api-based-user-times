<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@admin.com',
            ],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'isAdmin' => User::ADMIN_TYPE,
            ]
        );
    }
}
