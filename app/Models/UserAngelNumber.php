<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAngelNumber extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function angelNumber()
    {
        return $this->belongsTo(AngelNumber::class);
    }
}
