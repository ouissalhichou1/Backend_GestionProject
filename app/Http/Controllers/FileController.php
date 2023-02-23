<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
  function SaveFile(Request $request, $id_user ,$id_project){ 
    try{
     $file = new File;
     $file->path =$request->input('path');
     $file->type =$request->input('type');
     $file->id_user = $id_user;
     $file->id_project =$id_project;
     $file->save();
     return CustomResponse::buildResponse("created successfully",$file ,201 );
    }
    catch(QueryException $e){
      $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
      return CustomResponse::buildResponse("error",$body ,500 ); 
    }
  }
  function DeleteFile(Request $request,$id_file){
    $file = DB::delete('delete from file where id = ?',[$id_file]);
    return CustomResponse::buildResponse("deleted successfully",'',200 );
  }
  
}

