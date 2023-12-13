<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        if ($this->authenticate($request, $guards) && $request->user()->tokenCan('token-name') && now()->lessThan($request->user()->token()->expires_at)) {
            return $next($request);
        }

        if($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
    }
}