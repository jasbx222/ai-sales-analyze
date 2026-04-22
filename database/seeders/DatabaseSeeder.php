<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['phone' => '07700550000'],
            [
                'name' => 'Test admin',
                'role' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
            ]
        );
    }
}
