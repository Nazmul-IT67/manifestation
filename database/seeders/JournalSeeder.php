<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moods = ['Happy', 'Sad', 'Excited', 'Anxious', 'Calm', 'Grateful'];
        
        for ($i = 1; $i <= 10; $i++) {
            DB::table('journals')->insert([
                'user_id'         => rand(1, 5),
                'journal_type_id' => rand(1, 3),
                'title'           => 'My Journal Entry ' . $i,
                'description'     => 'This is a sample journal description for day ' . $i . '. Today was a productive day and I learned something new about Laravel seeders.',
                'mood_tag'        => $moods[array_rand($moods)],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}