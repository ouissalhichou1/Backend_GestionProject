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

        
            $project = new Project;
            $project->sujet =$request->input('sujet');
            $project->filiere =$request->input('filiere');
            $project->filiere =$request->input('filiere');
            $project->NbrPersonnes = $request->input('NbrPersonnes');
            $project->description =$request->input('description');
            $project->save();
            return response()->json([
                'status' => 'success',
                'project' => $project,
                ]);
        
    }

    function GetAllProjects(){
     $projects = Project::all();
     $data = [];

     foreach ($projects as $project) {
        $result = DB::select('select name ,surname from users where id = ?', [ $project->id_user]);
        $result = array_map(function ($value) {return (array)$value;}, $result);
        $project->owner_name = $result[0]["name"]." ".$result[0]["surname"];
        $data[] = $project;
     }

     return response()->json([
        'status' => 'success',
        'data' => $data,
        ]);
    }

    function DeleteProject(Request $request, $id_user){
        $projects = DB::delete('delete from projects where id_user =? and id  = ?',[$id_user, $request->id_project]);
        return response()->json([
            'status' => 'success',
            ]);
    }
    function ModifyProject(Request $request , $id_user){
        $id_project = $request->id_project;
        $updated_project = DB::update('update projects set sujet = ?, filiere = ?, description = ? where id_user = ? and id = ?',[$request->sujet ,$request->filiere, $request->description,$id_user,$id_project]);
        $updated_project = Project::find($id_project);
        return response()->json([
            'status' => 'success',
            'project' => $updated_project,
            ]);    
    }
    
}
