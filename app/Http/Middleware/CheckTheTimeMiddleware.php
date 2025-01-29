<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTheTimeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('delete') && auth()->user()->isAdmin == 1) {
            return $next($request);
        }

        $user = User::with('durations')->where('id', Auth::user()->id)->first();

        $now = now()->startOfSecond();

        if (!$user->durations) {
            return to_route('dashboard')->with('error', 'You have no time to use this functionality.');
        }
        if ($now > $user->durations->end_time) {
            $user->durations()->delete();
            return to_route('dashboard')->with('error', 'Your time has expired.');
        }

        if ($now >= $user->durations->start_time && $now <= $user->durations->end_time) {
            return $next($request);
        }

        return to_route('dashboard')->with('error', 'wating for your time.');
    }
}
