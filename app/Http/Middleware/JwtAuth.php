<?php

namespace App\Http\Middleware;

use App\Http\JwtService;
use Closure;

class JwtAuth
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
        if ($request->header('Authorization') == "" || $request->header('Authorization') == null) {
            return response()->json([
                'status' => false,
                'message' => 'Access Denind',
            ], 500);
        }
        return $next($request);
    }
}
