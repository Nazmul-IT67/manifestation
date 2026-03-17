<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    // Admin middleware e eta add koro AJAX request er jonno
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'unauthenticated'], 401);
            }
            return abort(401, 'Unauthorized');
        }

        if (! $user->status) {
            Auth::logout();
            if ($request->expectsJson()) {
                return response()->json(['status' => 'disabled'], 403);
            }
            return abort(403, 'Your account is disabled.');
        }

        if ($user->role == 'admin') {
            return $next($request);
        }

        Auth::logout();
        if ($request->expectsJson()) {
            return response()->json(['status' => 'forbidden'], 403);
        }
        return abort(403, 'Access denied');
    }
}
