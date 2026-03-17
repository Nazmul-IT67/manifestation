<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user'           => [
                'id'         => $this->user?->id,
                'name'       => $this->user?->name,
                'avatar'     => $this->user?->image ? $this->user->image : null,
            ],
            'id'           => $this->id,
            'post_id'      => $this->post_id,
            'comment_text' => $this->comment_text ?? null,
            'likes_count'        => $this->likes_count,
            'posted_time'  => $this->human_time,
        ];
    }
}
