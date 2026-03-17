<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'password',
        'remember_token',
        'reset_password_token',
        'token_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function details()
    {
        return $this->hasOne(UserDetails::class, 'user_id')->withDefault();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('start_date', '<=', Carbon::today())
            ->where('end_date', '>=', Carbon::today())
            ->exists();
    }


    /**
     * NOTE: Code below this point was added by Alamin.
     * Please add any new code above this comment.
     */

    //relations

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }


    //Scope Method_______________________________________

    public function scopeSubscribed(Builder $query): Builder
    {
        return $query->whereHas('subscriptions', function (Builder $q) {
            $q->where('status', 'active')
                ->where('start_date', '<=', Carbon::today())
                ->where('end_date', '>=', Carbon::today());
        });
    }
}
