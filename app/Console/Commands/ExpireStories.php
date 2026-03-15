<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class ExpireStories extends Command
{
    protected $signature   = 'stories:expire';
    protected $description = 'Expire stories after 24 hours';

    public function handle()
    {
        Post::where('post_type', 'story')
            ->where('is_active', true)
            ->where('expires_at', '<=', now())
            ->update(['is_active' => false]);

        $this->info('Stories expired successfully');
    }
}