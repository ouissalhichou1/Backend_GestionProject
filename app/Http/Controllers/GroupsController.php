<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class GroupsController extends Controller{
  
  function SaveGroup(Request $request, $id_group_admin){ 

    try{
      $group = new Group;
      $group->$id_group_admin =$$id_group_admin;
      $group->id_user2 =$request->id_user2;
      $group->id_user3 =$request->id_user3;
      $group->id_user4 =$request->id_user4;
      $group->id_user5 =$request->id_user5;
      $group->save();
      return CustomResponse::buildResponse("created successfully",$group ,201 );
    }
    catch(QueryException $e){
      $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
      return CustomResponse::buildResponse("error",$body ,500 ); 
    }
  }
  function DeleteGroup(Request $request ,$id_group_admin){
      $group = DB::delete('delete from groups where id = ?',[$id_group_admin]);
      return CustomResponse::buildResponse("deleted successfully",'',200 );
  }
  function QuitGroup(Request $request ,$id_student){
    $id_group = DB::select('select * from groups where id_user2 =? or id_user3 =? or id_user4 =? or id_user5 =?', [$id_student,$id_student,$id_student,$id_student]);
    $id_group=array_map(function ($value) {return (array)$value;}, $id_group);
    $group =$id_group;
    unset($group[0]["id"]);
    unset($group[0]["id_group_admin"]);
    $key = array_search($id_student, $group[0]);
    $group = DB::update("update groups set ". $key."= NULL where id=?",[$id_group[0]["id"]]);
    return CustomResponse::buildResponse("deleted successfully",'',200 );
  }
  function AdminQuitGroup(Request $request, $id_group_admin){
    $id_group = DB::select('select * from groups where id_group_admin=?', [$id_group_admin]);
    $id_group=array_map(function ($value) {return (array)$value;}, $id_group);
    //print_r($id_group);
    $id_group_updated = DB::update("update groups set id_group_admin =?,id_user2=?,id_user3=?,id_user4=?,id_user5=NULL where id=?",[$id_group[0]["id_user2"],$id_group[0]["id_user3"],$id_group[0]["id_user4"],$id_group[0]["id_user5"],$id_group[0]["id"]]);
    return CustomResponse::buildResponse("deleted successfully",'',200 );
  }
}
