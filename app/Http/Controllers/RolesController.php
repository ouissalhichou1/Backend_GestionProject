<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    function SaveRole(Request $request){

        try{
            $roles = new Role;
            $roles->RoleName =$request->input('RoleName');
            $roles->save();
            return CustomResponse::buildResponse("created successfully",$roles ,201 );
        }
       catch(QueryException $e){
          $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
          return CustomResponse::buildResponse("error",$body ,500 ); 
        }
    }
}
