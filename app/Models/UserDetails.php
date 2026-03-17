<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'phone'                    => null,
        'height'                   => null,
        'weight'                   => null,
        'age'                      => null,
        'experience_level'         => null,
        'primary_goal'             => null,
        'default_session_duration' => null,
        'preferred_sound_profile'  => null,
        'daily_reminder_time'      => null,
        'stat_manifests'           => 0,
        'stat_streak'              => 0,
        'stat_minutes'             => 0,
        'location'                 => null,
        'bio'                      => null,
    ];
}
