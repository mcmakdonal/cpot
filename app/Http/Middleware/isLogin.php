<?php

namespace App\Http\Middleware;

use Closure;

class isLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = "")
    {
        if (!(\Cookie::get('ad_id') !== null)) {
            \Cookie::forget('ad_id');
            \Cookie::forget('per_id');
            abort(404);
        }

        if (!(\Cookie::get('per_id') !== null)) {
            \Cookie::forget('ad_id');
            \Cookie::forget('per_id');
            abort(404);
        }

        // if (!(\Cookie::get('ad_permission') !== null)) {
        //     \Cookie::forget('ad_id');
        //     \Cookie::forget('ad_role');
        //     \Cookie::forget('ad_permission');
        //     abort(404);
        // }

        // dd(!\Helper::instance()->check_role($role));

        // if ((\Cookie::get('ad_permission') != "S")) {
        //     if (!\Helper::instance()->check_role($role)) {
        //         abort(404);
        //     }
        // }

        return $next($request);
    }
}
