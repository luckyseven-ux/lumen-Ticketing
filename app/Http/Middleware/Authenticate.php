<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Factory as Auth;


class Authenticate
{
    protected $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth; // Simpan instance AuthManager
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->auth->guard('api')->guest()) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}