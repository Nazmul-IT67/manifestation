<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            UserActivity::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'login_date' => Carbon::today()->toDateString()
                ],
                [
                    'is_active' => true
                ]
            );
        }

        return $next($request);
    }
}