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
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = User::where('id', Auth::user()->id)->first();

        if($user && $user->run_one_time === 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'API endpoint not found',
                    'data' => null
                ], 404);
            }

            abort(404);

        }
        return $next($request);
    }
}
