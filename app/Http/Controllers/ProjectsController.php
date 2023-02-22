<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Database\QueryException;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;

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
    
}
