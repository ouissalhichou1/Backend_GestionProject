<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\NewPasswordEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','NewPassword']]);
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
        }catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                // Get the error message
                $errorMessage = $e->getMessage();
        
                // Check if the error message contains a known constraint violation
                if (strpos($errorMessage, 'users_email_unique') !== false) {
                    return response()->json(['message' => 'The email must be unique'], 401);
                } else if (strpos($errorMessage, 'users_apogee_unique') !== false) {
                    return response()->json(['message' => 'The apogee must be unique'], 402);
                } else if (strpos($errorMessage, 'users_code_unique') !== false) {
                    return response()->json(['message' => 'The code must be unique'], 403);
                } else {
                    // If the constraint violation is not known, return a generic error message
                    return response()->json(['message' => 'An error occurred'], 500);
                }
            } else {
                // If the error is not a unique constraint violation, return a generic error message
                return response()->json(['message' => 'An error occurred'], 500);
            }
        }
        
        
    }
    public function NewPassword(Request $request){
        $user_email = $request->input('email');
        $user = DB::table('users')
            ->select('id')
            ->where('email', $user_email)
            ->first(); 
        $result = DB::select('select name, surname from users where id = ?', [$user->id]);
        $result = array_map(function ($value) {return (array) $value;}, $result);
        $userName = $result[0]["name"] . " " . $result[0]["surname"];

        if ($user) {
            $newPassword = Str::random(10);
            $update = DB::table('users')
                ->where('id', $user->id)
                ->update(['password' => bcrypt($newPassword)]); // Hash the new password before updating
    
            if ($update) {
                Mail::to($user_email)->send(new NewPasswordEmail($userName,$newPassword));
                return response()->json([
                    'status' => '200',
                    'message' => 'Check your email. A new password has been sent to you.',
                ]);
            }
        }
    
        return response()->json([
            'status' => '404',
            'message' => 'User not found.',
        ]);
    }
    public function updatePassword(Request $request, $id_user){
        $newPassword = $request->input('password');
        $update = DB::table('users')
            ->where('id', $id_user)
            ->update(['password' => $newPassword]); //
        return response()->json([
            'status'=>'200',
            'message'=>'good',
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