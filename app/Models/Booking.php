<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    protected $casts = ['selected_paths' => 'array'];

    protected $hidden = ['created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sessionType()
    {
        return $this->belongsTo(SessionType::class, 'session_type_id');
    }
}
