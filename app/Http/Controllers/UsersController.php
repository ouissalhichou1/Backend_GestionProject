<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\DB;
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
    function SaveSAtudentsFromFile(Request $request){
        if ($request->file('etudiantsS6')->isValid()) {  // test if the file is valid
                $file = $request->file('etudiantsS6');      // then get it from the request
                if($file->extension() !== 'csv'){
                    return CustomResponse::buildResponse("file format nor acceptable",'',406 );
                }
                // before reading from the file we need to open it to get the stream of data so we can read from it using fgetcsv
                $stream = fopen($file, 'r'); // here we open the file 
                $data = array();
                $header = null;
                if($stream !== false){ // then we check if this file is opened successfully
                    while (($row = fgetcsv($stream, 1000, ",")) !== FALSE){ 
                        // then we keep reading line by line until we reach the end
                        if(!$header){
                            $header = $row;
                        }else{
                            $data[] = array_combine($header, $row);
                        }
                    }
                    // after we read all the lines we need to close the stream
                    fclose($stream);
                } 
            $insertQuery = DB::table("users")->insert($data);
            return CustomResponse::buildResponse("created successfully",$data ,201);
        }
    }
    function SaveProfessorFromFile(Request $request){
            if ($request->file('professors')->isValid()) {  // test if the file is valid
                    $file = $request->file('professors');      // then get it from the request
                    if($file->extension() !== 'csv'){
                        return CustomResponse::buildResponse("file format nor acceptable",'',406 );
                    }
                    // before reading from the file we need to open it to get the stream of data so we can read from it using fgetcsv
                    $stream = fopen($file, 'r'); // here we open the file 
                    $data = array();
                    $header = null;
                    if($stream !== false){ // then we check if this file is opened successfully
                        while (($row = fgetcsv($stream, 1000, ",")) !== FALSE){ 
                            // then we keep reading line by line until we reach the end
                            if(!$header){
                                $header = $row;
                            }else{
                                $data[] = array_combine($header, $row);
                            }
                        }
                        // after we read all the lines we need to close the stream
                        fclose($stream);
                    } 
                $insertQuery = DB::table("users")->insert($data);
                return CustomResponse::buildResponse("created successfully",$data ,201);
        }
    }
    



}
