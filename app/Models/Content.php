<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Content extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    protected $casts = [
        'is_premium' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
