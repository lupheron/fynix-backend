<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UserAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = substr($request->header('Authorization'), 7);
        $u = DB::table('users')->where('remember_token', $token)->first();
        // dd($u);

        if (!isset($u)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}


// $2y$12$JJbFNrdagXXirToeZraCVuy17q8e/m/h/V8eM9VUyeOR2lioWdXg.

// JzpEv2zzikoNQ05W7mbFuWPJPTrvgq5YP4kJBJkc4beab288