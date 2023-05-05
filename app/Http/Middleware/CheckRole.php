<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $user_role = $payload->get('role');
        }
        catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        if (!$user_role || $user_role !== $role) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
