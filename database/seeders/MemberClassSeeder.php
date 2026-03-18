<?php

namespace Database\Seeders;

use App\Models\MemberClass;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MemberClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['code' => '1', 'name' => 'Kelas A', 'default_dues_amount' => 5000000],
            ['code' => '2', 'name' => 'Kelas B', 'default_dues_amount' => 3000000],
            ['code' => '3', 'name' => 'Kelas C', 'default_dues_amount' => 1500000],
            ['code' => '4', 'name' => 'Kelas D', 'default_dues_amount' => 750000],
        ];

        foreach ($classes as $c) {
            MemberClass::updateOrCreate(
                ['code' => $c['code']],
                $c
            );
        }
    }
}
