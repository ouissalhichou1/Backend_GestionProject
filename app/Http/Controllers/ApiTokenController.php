<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

class ApiTokenController extends Controller
{
    /*public function generateToken(Request $request, $id, $role)
    {   $payload = [
        'sub' => $user->id,
        'role' => $user->role,
        'id_user' => $user->id,
        'iat' => time(),
        'exp' => time() + config('jwt.ttl'),
    ];
    $token = JWTAuth::encode($payload);

        $user = User::findOrFail($id);
        $token = $user->createToken($role)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }*/
}
