<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Application;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationsController extends Controller
{
    function SaveApplication(Request $request,$id_group_admin,$id_project){
        try{
            $id_groups = DB::select('select id from groups where id_group_admin = :id_group_admin', ['id_group_admin' => $id_group_admin]);
            $applications = new Application;
            $applications->id_project = $id_project;
            $applications->id_group = $id_groups;
            $applications->accepted = 'false';
            $applications->save();
            return CustomResponse::buildResponse("created successfully",$applications ,201 );
        }
        catch(QueryException $e){
            $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
            return CustomResponse::buildResponse("error",$body ,500 ); 
        }
        
    }

    function ResponseforApplication(Resquest $request){
        
        
    }
}
