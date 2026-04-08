<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SessionTime;

class SessionTimeSeeder extends Seeder
{
    public function run(): void
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($days as $day) {
            SessionTime::updateOrCreate(
                [
                    'expert_id'    => 1,
                    'day'          => $day,
                    'session_date' => null,
                ],
                [
                    'start_time' => '09:00:00',
                    'end_time'   => '17:00:00',
                    'is_active'  => true,
                ]
            );
        }

        SessionTime::updateOrCreate(
            [
                'expert_id'    => 1,
                'session_date' => '2026-04-10',
            ],
            [
                'start_time' => '10:00:00',
                'end_time'   => '18:00:00',
                'is_active'  => true,
            ]
        );
    }
}