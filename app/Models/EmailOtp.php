<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailOtp extends Model
{
    use HasFactory;
    
    public $timestamps = true;
    
    protected $fillable = ['user_id', 'verification_code', 'expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
