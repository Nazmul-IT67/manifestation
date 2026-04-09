<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        $userIds = User::pluck('id')->toArray();
        $postIds = Post::pluck('id')->toArray();

        if (empty($userIds) || empty($postIds)) {
            $this->command->info('Age User ebong Post seeder run korun!');
            return;
        }

        foreach (range(1, 50) as $index) {
            Comment::create([
                'user_id'      => $userIds[array_rand($userIds)],
                'post_id'      => $postIds[array_rand($postIds)],
                'comment_text' => $faker->sentence(10),
                'likes_count'  => rand(0, 50),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}