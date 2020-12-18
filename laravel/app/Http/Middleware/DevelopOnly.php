<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DevelopOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('app.env') === 'production') {
            abort(403);
        }

        return $next($request);
    }
}
