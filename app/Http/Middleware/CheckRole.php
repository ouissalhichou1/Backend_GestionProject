<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            $token = JWTAuth::parseToken();
            $user = $token->authenticate();
            $role = $user->getClaim('role');

            if (!in_array($role, $roles)) {
                abort(403, 'Unauthorized action.');
            }
        } catch (\Exception $e) {
            abort(401, 'Unauthorized.');
        }

        return $next($request);

    }
}