<?php

namespace App\Http\Middleware;

use App\Models\TimeDelete;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTheTimeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $time = TimeDelete::where('user_id', Auth::user()->id)->first();

        $now = now()->startOfSecond();

        if($time == NULL){
            return 'insert time first';
        }

        if ($time->start_time <= $now && $now <= $time->end_time) {
            return $next($request);
        }

        abort(404);
    }
}
