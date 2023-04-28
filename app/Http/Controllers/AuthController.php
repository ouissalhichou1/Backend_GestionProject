<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
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
        // check if the user is registerd!!
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
       $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'id_user'=>$user->id,
                'name'=>$user->name,
                'surname'=>$user->surname,
                'role'=>$user->roles[0]->RoleName,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
                ]);
    }

    public function register(Request $request){
        try{
        $request->validate([
                'name' => 'required|string|max:255',
                'surname' =>'required|string|max:255',
                'email' => 'required|string|email:|max:255',
                'password' =>'required|string|min:6',
                'code'=>'integer|min:3',
                'apogee'=>'integer|min:8',
                'filiere'=>'string|max:255',
                'specialite' =>'string|max:255',
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
                'id_user'=>$user->id,
                'name'=>$user->name,
                'surname'=>$user->surname,
                'role'=>$user->roles[0]->RoleName,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
        catch(QueryException $e){
            $body = ["errorCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
            return CustomResponse::buildResponse("error",$body ,500 ); 
        };
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