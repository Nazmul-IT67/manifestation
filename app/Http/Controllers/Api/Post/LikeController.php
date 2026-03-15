<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use ApiResponse;

    public function postLike(Request $request, Post $post)
    {
        return $this->toggle($request, $post, 'Post');
    }

    public function commentLike(Request $request, Comment $comment)
    {
        return $this->toggle($request, $comment, 'Comment');
    }

    private function toggle(Request $request, $model, string $type)
    {
        $user  = $request->user();
        $liked = $model->likes()->where('user_id', $user->id)->exists();

        if ($liked) {
            $model->likes()->where('user_id', $user->id)->delete();
            $model->decrement('likes_count');
            $message = $type . ' unliked';
        } else {
            $model->likes()->create(['user_id' => $user->id]);
            $model->increment('likes_count');
            $message = $type . ' liked';
        }

        return $this->success([
            'likes_count' => $model->fresh()->likes_count,
            'is_liked'    => !$liked,
        ], $message);
    }
}