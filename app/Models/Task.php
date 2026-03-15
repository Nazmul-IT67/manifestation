<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $casts = ['is_completed' => "boolean"];

    protected $hidden = ['created_at','updated_at'];



}
