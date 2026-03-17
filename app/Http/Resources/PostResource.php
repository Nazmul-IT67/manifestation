<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $comment_count = $this->comments()->count();
        return [
            'user' => $this->whenLoaded('user', [
                'id'     => $this->user?->id,
                'name'   => $this->user?->name,
                'avatar' => $this->user?->image ? $this->user->image: null,
                'is_premium' =>$this->user?->is_premium ?? null,  // feature change after completed subscriptions
            ]),
            'id'          => $this->id,
            'content'     => $this->content ?? null,
            'media_path'  => $this->media_path ? $this->media_path : null,
            'media_type'  => $this->media_type,
            'post_type'   => $this->post_type,
            'background'  => $this->background ?? null,
            'tags'        => $this->tags ?? null,
            'is_active'   => $this->is_active,
            'feelings'    => $this->feelings,
            'likes_count' => $this->likes_count,
            'comment_count' =>$comment_count,
            'posted_time'  => $this->human_time,

        ];
    }
}
