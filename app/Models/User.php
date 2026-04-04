<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image',
        'is_active',
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
            'password'          => 'hashed',
        ];
    }

    public function details()
    {
        return $this->hasOne(UserDetails::class, 'user_id')->withDefault();
    }

    /**
     * NOTE: Code below this point was added by Alamin.
     * Please add any new code above this comment.
     */

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('start_date', '<=', Carbon::today())
            ->where('end_date', '>=', Carbon::today())
            ->exists();
    }

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

    public function angelNumber()
    {
        return $this->hasOne(UserAngelNumber::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
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

    public function currentSubscription()
    {
        return $this->hasOne(UserSubscription::class)
                    ->where('status', 'active')
                    ->whereDate('end_date', '>=', now())
                    ->latest();
    }
}