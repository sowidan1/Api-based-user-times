<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTimesPostiveMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('delete') && auth()->user()->isAdmin == 1) {
            return $next($request);
        }

        if (auth()->user()->times <= 0) {
            return redirect()->route('dashboard')->with('error', "You don't have enough times.");
        }

        return $next($request);
    }
}
