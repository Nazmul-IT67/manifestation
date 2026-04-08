<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SessionType;

class SessionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name'     => 'Virtual Session',
                'duration' => 60,
                'price'    => 50.00,
            ],
            [
                'name'     => 'One-on-One Session',
                'duration' => 45,
                'price'    => 75.00,
            ],
        ];

        foreach ($types as $type) {
            SessionType::create($type);
        }
    }
}