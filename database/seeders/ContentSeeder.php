<?php

namespace Database\Seeders;

use App\Models\Content;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Content::create([
                'category_id'  => rand(1, 5),
                'title'        => 'Sample Content Title ' . $i,
                'sub_title'    => 'Short description for content ' . $i,
                'thumbnail'    => 'thumbnails/sample' . rand(1, 3) . '.jpg',
                'content_url'  => 'https://example.com/video' . $i . '.mp4',
                'content_type' => $i % 2 == 0 ? 'video' : 'audio',
                'duration'     => '10:30',
                'is_premium'   => rand(0, 1),
                'is_active'    => 1,
            ]);
        }
    }
}