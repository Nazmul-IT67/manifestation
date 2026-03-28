<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AffirmationSeeder extends Seeder
{
    public function run(): void
    {
        $affirmations = [
            [
                'category_id' => 1,
                'quote'       => 'Believe you can and you\'re halfway there.',
                'video_url'   => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 1,
                'quote'       => 'You are enough just as you are.',
                'video_url'   => 'https://www.youtube.com/watch?v=ZXsQAXx_ao0',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 2,
                'quote'       => 'Every day is a second chance.',
                'video_url'   => 'https://www.youtube.com/watch?v=g4mHPeMGTJM',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 2,
                'quote'       => 'Your only limit is your mind.',
                'video_url'   => 'https://www.youtube.com/watch?v=mgmVOuLgFB0',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 3,
                'quote'       => 'Small steps every day lead to big results.',
                'video_url'   => 'https://www.youtube.com/watch?v=StTqXEQ2l-Y',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('affirmations')->insert($affirmations);
    }
}