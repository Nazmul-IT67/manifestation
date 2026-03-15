<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralPostController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $posts = $request->user()->posts()->latest()->get();
        return $this->success(PostResource::collection($posts->load('user')), 'Posts fetched successfully');
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();

        if ($request->post_type === 'story') {
            $data['expires_at'] = now()->addHours(24);
        }

        if ($request->hasFile('media_path')) {
            $file               = $request->file('media_path');
            $data['media_path'] = uploadFile($file, 'uploads/posts');
            $data['media_type'] = getFileType($data['media_path']);
        }

        $post = $request->user()->posts()->create($data);

        return $this->success($post, 'Post created successfully', 201);
    }

    public function show(Request $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        return $this->success(new PostResource($post->load('user')), 'Post fetched successfully');
    }

    public function update(PostRequest $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        $data = $request->validated();

        if ($request->hasFile('media_path')) {
            deleteFile($post->media_path);
            $file               = $request->file('media_path');
            $data['media_path'] = uploadFile($file, 'uploads/posts');
            $data['media_type'] = getFileType($data['media_path']);
        }

        $post->update($data);
        return $this->success($post, 'Post updated successfully');
    }

    public function destroy(Request $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        if ($post->media_path) {
            deleteFile($post->media_path);
        }

        $post->delete();

        return $this->success(null, 'Post deleted successfully');
    }


    // New feed section here________________________________________________________

    public function newFeed(Request $request)
    {
        $story_section = Post::with('user')->where('post_type', 'story')
            ->where('is_active', true)->where('expires_at', '>', now())
            ->select(DB::raw('MAX(id) as id'), 'user_id')->groupBy('user_id')
            ->orderByDesc(DB::raw('MAX(created_at)'))->get()
            ->map(fn($story) => [
                'post_id'     => $story->id,
                'user'   => [
                    'name'   => $story->user?->name,
                    'image'  => $story->user?->image ?? null,
                ],
            ]);

        $posts = Post::with('user')->where('is_active', true)->where('post_type', 'post')->inRandomOrder()->get();

        return $this->success([
            'stories' => $story_section,
            'posts'   => PostResource::collection($posts),
        ], 'Feed fetched successfully');
    }
}
