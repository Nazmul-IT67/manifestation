<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('bookings')->insert([
                'user_id'         => $faker->numberBetween(1, 10),
                'expert_id'       => $faker->numberBetween(1, 5),
                'session_type_id' => $faker->numberBetween(1, 3),
                'booking_date'    => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
                'booking_time'    => $faker->time('H:i'),
                'status'          => $faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}