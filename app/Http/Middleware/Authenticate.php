<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
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
        try {
            $this->authenticate($request, $guards);
        } catch (\Exception $e) {
            $user = auth()->user();
            if ($user) {
                Log::info('Authenticated user: ', ['id' => $user->id, 'name' => $user->name]);
            } else {
                Log::info('No authenticated user', []); // Add an empty array as the second argument
            }
            throw $e;
        }

        // Continue processing the request
        return $next($request);
    }
}