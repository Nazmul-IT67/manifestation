<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->info('Age User seeder run korun, karon user chara post hobe na!');
            return;
        }

        $posts = [
            [
                'user_id' => $userIds[array_rand($userIds)],
                'content' => 'Feeling blessed today! #Life #Blessed',
                'media_path' => 'uploads/posts/sample1.jpg',
                'media_type' => 'image',
                'post_type' => 'post',
                'background' => null,
                'feelings' => 'happy',
                'tags' => json_encode(['life', 'blessed']),
                'expires_at' => null,
                'is_active' => true,
                'likes_count' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'content' => 'This is a secret journal entry.',
                'media_path' => null,
                'media_type' => null,
                'post_type' => 'journal',
                'background' => '#f0f0f0',
                'feelings' => 'thoughtful',
                'tags' => json_encode(['secret', 'journal']),
                'expires_at' => null,
                'is_active' => true,
                'likes_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'content' => 'Checking out the new story feature!',
                'media_path' => 'uploads/posts/story.mp4',
                'media_type' => 'video',
                'post_type' => 'story',
                'background' => null,
                'feelings' => 'excited',
                'tags' => json_encode(['story', 'video']),
                'expires_at' => Carbon::now()->addHours(24),
                'is_active' => true,
                'likes_count' => rand(5, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        Post::insert($posts);
    }
}