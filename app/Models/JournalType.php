<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalType extends Model
{
    protected $guarded = [];
    
    protected $hidden = ['created_at','updated_at'];
    protected $casts = ['is_active' => 'boolean'];
}
