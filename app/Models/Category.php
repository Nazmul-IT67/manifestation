<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    protected $casts = ['is_active' => 'boolean'];
    protected $hidden = ['created_at','updated_at',];
}
