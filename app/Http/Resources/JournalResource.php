<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'mood_tag'    => $this->mood_tag,
            'posted_time' => $this->created_at,
            'type'        => $this->whenLoaded('journalType', [
                'id'    => $this->journalType?->id,
                'title' => $this->journalType?->title,
                'icon'  => $this->journalType?->icon,
            ]),
        ];
    }
}