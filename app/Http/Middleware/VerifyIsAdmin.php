<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        else {
            return response()->json([
                'error' => true,
                'message' => 'Failed to grant access, you can\'t access this route',
                'data' => []
            ], 401);
        }
    }
}
