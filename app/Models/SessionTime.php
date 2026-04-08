<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SessionTime extends Model
{
    protected $fillable = ['expert_id', 'session_date', 'day', 'start_time', 'end_time', 'is_active'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->session_date) {
                $model->day = Carbon::parse($model->session_date)->format('l');
            }
        });
    }
}
