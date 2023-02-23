<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use Illuminate\Support\Facades\DB;

class ApplicationsController extends Controller
{
    function SaveApplication(Request $request,$id_group_admin,$id_project){
        try{
            $id_groups = DB::select('select id from groups where id_group_admin = :id_group_admin', ['id_group_admin' => $id_group_admin]);
            $id_groups=array_map(function($value){return (array)$value;},$id_groups);
            $applications = new Application;
            $applications->id_projet = $id_project;
            $applications->id_group = $id_groups[0]["id"];
            $applications->save();
            return CustomResponse::buildResponse("created successfully",$applications ,201 );
        }
        catch(QueryException $e){
            $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
            return CustomResponse::buildResponse("error",$body ,500 ); 
        }
        
        
    }
    function ResponseforApplication(Request $request, $id_application){
        $response = $request->response;
        $applications = DB::update('update applications set response = ? where id = ?',[$response , $id_application]);
        $updated_application = Application::find($applications);
        return CustomResponse::buildResponse("updated successfully",$updated_application ,200 );
    }
    function DeleteApplication(Request $request, $id_application){
        $applications = DB::delete('delete from applications where id = ?',[$id_application]);
        return CustomResponse::buildResponse("deleted successfully",'',200 );
    }
}  


