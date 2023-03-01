<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ProjectsController extends Controller{

    function SaveProject(Request $request,$id){

        try{
            $projects = new Project;
            $projects->sujet =$request->input('sujet');
            $projects->filiere =$request->input('filiere');
            $projects->id_user = $id;
            $projects->description =$request->input('description');
            $projects->save();
            return CustomResponse::buildResponse("created successfully",$projects ,201 );
        }
       catch(QueryException $e){
           $body = ["erroCode" => ExceptionHandler::getErrorCode($e), "errorMessage" => ExceptionHandler::getErrorMessage($e)];
           return CustomResponse::buildResponse("error",$body ,500 );
        }
    }
    function getProjects(){
        return CustomResponse::buildResponse("fetched",Project::all() ,200 );
    }
    function DeleteProject(Request $request, $id_project){
        $projects = DB::delete('delete from projects where id = ?',[$id_project]);
        return CustomResponse::buildResponse("deleted successfully",'',200 );
    }
    function ModifyProject(Request $request , $id_project){
        $updated_project = DB::update('update projects set sujet = ?, filiere = ?, description = ? where id = ?',[$request->sujet ,$request->filiere, $request->description,$id_project]);
        $updated_project = Project::find($id_project);
        return CustomResponse::buildResponse("updated successfully",$updated_project,200 );
    }
    
}
