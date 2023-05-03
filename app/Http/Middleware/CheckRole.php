<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
//use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            $token = JWTAuth::parseToken();
            $user = $token->authenticate();
            $role = $user->getClaim('role');

            if (!in_array($role, $roles)) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
