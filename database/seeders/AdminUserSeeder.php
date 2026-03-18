<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@persi.com'],
            [
                'name' => 'Admin PERSI',
                'password' => Hash::make('password123'),
                'role' => 'admin'
            ]
        );
    }
}
