<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AngelNumber extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
    ];
}
