<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Database\QueryException;
use App\Models\ExceptionHandler;

class GroupsController extends Controller{
  
  function SaveGroup(Request $request, $id_group_admin){ 

    try{
      $group = new Group;
      $group->$id_group_admin =$$id_group_admin;
      $group->id_user2 =$request->id_user2;
      $group->id_user3 =$request->id_user3;
      $group->id_user4 =$request->id_user4;
      $group->id_user5 =$request->id_user5;
      $group->id_project=$request->id_project;
      $group->save();
      return CustomResponse::buildResponse("created successfully",$group ,201 );
    }
    catch(QueryException $e){
      $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
      return CustomResponse::buildResponse("error",$body ,500 ); 
    }
  }
  
  function application(Request $request){
    
  }
}
