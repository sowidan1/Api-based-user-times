<?php

namespace App\Http\Middleware;

use App\Models\Flag;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsRunBefore
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('delete') && auth()->user()->isAdmin == 1 ) {
            return $next($request);
        }

        $user = User::where('id', Auth::user()->id)->first();

        if($user && $user->run_one_time == 1) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'You have not run the one time api.');
    }
}
