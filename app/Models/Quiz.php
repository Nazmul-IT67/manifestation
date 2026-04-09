<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = [];

    protected $casts = ['selected_paths' => 'array'];
    protected $hidden = ['created_at','updated_at'];

    public function angelNumber()
    {
        return $this->belongsTo(AngelNumber::class, 'angel_number_id');
    }
}
