<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','sendResetLinkEmail','reset']]);
    }

    public function login(Request $request){   
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
         $token = Auth::attempt($credentials);
         // check if the user is registered!!
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        //check if the user verified email
        $user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Please verify your email address.'], 403);
        }
        // Add custom claim for user role
        $customClaims = ['role' => $user->roles[0]->RoleName];
        $token = Auth::claims($customClaims)->refresh();
        return response()->json([
            'status' => 'success',
            'id_user' =>$user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'role' => $user->roles[0]->RoleName,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
    public function register(Request $request){

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
    
        try {
            $user = User::create([
                'name'=>$request->name,
                'surname'=>$request->surname,
                'code'=>$request->code,
                'apogee'=>$request->apogee,
                'filiere'=>$request->filiere,
                'specialite' =>$request->specialite,
                'email' =>$request->email,
                'password' => Hash::make($request->password),
                'email_verification_token' => Str::random(40),
            ]);
            event(new Registered($user));
            return response()->json([
                'status' => 'success',
                'message' => 'Registered successfully! Please verify your email address.',
                'id_user'=>$user->id,
                'name'=>$user->name,
                'surname'=>$user->surname,
                'role'=>$user->roles[0]->RoleName,
            ]);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062 && strpos($e->getMessage(), 'email')) {
                return response()->json(['message' => 'The email must be unique'], 401);
            } else if ($e->errorInfo[1] == 1062 && strpos($e->getMessage(), 'apogee')) {
                return response()->json(['message' => 'The apogee must be unique'], 402);
            } else if ($e->errorInfo[1] == 1062 && strpos($e->getMessage(), 'code')) {
                return response()->json(['message' => 'The code must be unique'], 403);
            } else {
                return response()->json(['message' => 'An error occurred'], 500);
            }
        }
    }
    public function sendResetLinkEmail(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->all());
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset password link sent to your email'], 200);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
    public function reset(Request $request) {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->all());
        }

        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        if ($response === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been successfully reset'], 200);
        }

        throw ValidationException::withMessages([
            'email' => [trans($response)],
        ]);
    }
    public function logout(Request $request){
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
    public function me(){
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),// select * from users
        ]);
    }
    public function refresh(){
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'id_user' =>$user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'role' => $user->roles[0]->RoleName,
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }//

}