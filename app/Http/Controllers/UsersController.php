<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;


class UsersController extends Controller
{
    function SaveProfessor(Request $request){

        try{
            $users = new User;
            $users->name =$request->input('name');
            $users->surname =$request->input('surname');
            $users->specialite =$request->input('specialite');
            $users->code =$request->input('code');
            $users->email =$request->input('email');
            $users->password = Hash::make($request->input('password'));
            $users->save();
            $users->roles()->attach([2,3]);
            return CustomResponse::buildResponse("created successfully",$users ,201 );
        }
       catch(QueryException $e){
           $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
           return CustomResponse::buildResponse("error",$body ,500 );
        }
    }
    function SaveStudent(Request $request){

        try{

            $users = new User;
            $users->name =$request->input('name');
            $users->surname =$request->input('surname');
            $users->apogee =$request->input('apogee');
            $users->filiere =$request->input('filiere');
            $users->email =$request->input('email');
            $users->password = Hash::make($request->input('password'));
            $users->save();
            $users->roles()->attach([2,4]);
            return CustomResponse::buildResponse("created successfully",$users ,201 );
         
        }   
        catch(QueryException $e){
            $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
            return CustomResponse::buildResponse("error",$body ,500 );
        }
    }
    function listUsers(Request $request){
       return User::all();
    }
    function UserProfile(Request $request){
       $users  = User::find($request->id);
       if ($users) { 
         return CustomResponse::buildResponse("user found",$users ,302);
        }
       else{
         //$body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
         return CustomResponse::buildResponse("user not found",'',204 );
        }
    }
    



}
