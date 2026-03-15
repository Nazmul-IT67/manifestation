<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    protected $casts = ['tags' => "array", 'is_active' => 'boolean'];

    protected $hidden = ['created_at', 'updated_at'];


    protected $appends = ['human_time'];

    public function getHumanTimeAttribute(): string
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
