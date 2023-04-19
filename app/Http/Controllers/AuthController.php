<?php

namespace App\Http\Controllers;

//use PHP_OS\JwtAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller
{


    /*protected function CreateCustomPayload($userId){
        $userName = DB::select('select name from users where id = ?',[$userId]);
        $userSurname = DB::select('select surname from users where id = ?',[$userId]);
        $userRole = DB::select('select role_id from role_users where user_id = ?',[$userId]);
        $customClaims = [
            'iss'=> config('app.name'),
            'sub'=>$userId,
            'name'=>$userName,
            'surname'=>$userSurname,
            'role'=>$userRole,
            'iat'=>time(),
            'exp'=>time()+60*60,
            //'my_claim'=>'some_value',
        ];
        return $customClaims;
    }*/

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {   
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
       $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email:|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'surname'=>$request->surname,
            'code'=>$request->code,
            'apogee'=>$request->apogee,
            'filiere'=>$request->filiere,
            'specialite' =>$request->specialite,
            'email' =>$request->email,
            'password' => Hash::make($request->password),
        ]);

        
        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            //'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function me()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}