<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JournalTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'title'       => 'Personal',
                'description' => 'Write about your personal thoughts and feelings.',
                'icon'        => 'personal.png',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Travel',
                'description' => 'Document your travel experiences and adventures.',
                'icon'        => 'travel.png',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Food',
                'description' => 'Share your favorite recipes and food experiences.',
                'icon'        => 'food.png',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Fitness',
                'description' => 'Track your fitness journey and workout routines.',
                'icon'        => 'fitness.png',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Mental Health',
                'description' => 'Express your mental health journey and mindfulness.',
                'icon'        => 'mental_health.png',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Gratitude',
                'description' => 'Write about things you are grateful for each day.',
                'icon'        => 'gratitude.png',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('journal_types')->insert($types);
    }
}