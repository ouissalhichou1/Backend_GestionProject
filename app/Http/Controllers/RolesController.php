<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use Illuminate\Support\Facades\DB;

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
    function GetUserRoles(Request $request ,$id_user){
        $results = DB::select('select RoleName from roles , role_users where role_users.user_id = ? and roles.id = role_users.role_id',[$id_user]);
        return CustomResponse::buildResponse("Found",$results ,302);
    }
    function GrantRoleForUser(Request $request , $id_user){
        $NewRole = DB::table('roles')->insert(['RoleName' => $request->NewRole]); 
        $idNewRole = DB::select('select id from roles where RoleName = ?',[$request->NewRole]);
        $idNewRole=array_map(function ($value) {return (array)$value;}, $idNewRole);
        $result = DB::table('role_users')->insert(['user_id'=> $id_user ,'role_id'=>$idNewRole[0]["id"]]);
        return CustomResponse::buildResponse("inserted",$result ,302);
    }
    function RevokeRoleForUser(Request $request ,$id_user){
        $id_role = DB::select('select id from roles where RoleName = ?',[$request->RoleName]);
        $id_role=array_map(function ($value) {return (array)$value;}, $id_role);
        $result = DB::delete('delete from role_users where user_id = ? and role_id =? ',[$id_user,$id_role[0]["id"]]);
        return CustomResponse::buildResponse("deleted successfully", '', 200);
    }
}
