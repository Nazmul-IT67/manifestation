<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\ApiResponse;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    use ApiResponse;

    public function index(Request $request, Post $post)
    {
        $comments = $post->comments()->with('user')->latest()->get();
        return $this->success(CommentResource::collection($comments), 'Comments fetched successfully');
    }

    public function store(Request $request, Post $post)
    {
        $validated = Validator::make($request->all(), [
            'comment_text' => 'required|string',
        ]);

        if ($validated->fails()) {
            return $this->error(null, $validated->errors()->first(), 422);
        }

        $comment = $post->comments()->create([
            'user_id'      => $request->user()->id,
            'comment_text' => $request->comment_text,
        ]);

        $comment->load('user');

        return $this->success(new CommentResource($comment), 'Comment added successfully', 201);
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        $validated = Validator::make($request->all(), [
            'comment_text' => 'required|string',
        ]);

        if ($validated->fails()) {
            return $this->error(null, $validated->errors()->first(), 422);
        }

        $comment->update([
            'comment_text' => $request->comment_text,
        ]);

        return $this->success(new CommentResource($comment), 'Comment updated successfully');
    }


    public function destroy(Request $request, Comment $comment)
    {
        if ($comment->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        $comment->delete();

        return $this->success(null, 'Comment deleted successfully');
    }

    public function show(Comment $comment)
    {
        $comment = $comment->only(['id', 'comment_text']);

        return $this->success($comment, 'Comment fetched successfully');
    }
}
