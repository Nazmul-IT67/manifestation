<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'updated_at',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function journalType()
    {
        return $this->belongsTo(JournalType::class);
    }
}
