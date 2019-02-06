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
            abort(404);
        }

        if (!(\Cookie::get('ad_role') !== null)) {
            abort(404);
        }

        if (!(\Cookie::get('ad_permission') !== null)) {
            abort(404);
        }

        // dd(!\Helper::instance()->check_role($role));

        if ((\Cookie::get('ad_permission') != "S")) {
            if (!\Helper::instance()->check_role($role)) {
                abort(404);
            }
        }

        return $next($request);
    }
}
