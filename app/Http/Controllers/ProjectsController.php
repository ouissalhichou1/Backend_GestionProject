<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Database\QueryException;

class ProjectsController extends Controller
{
    function SaveProject(Request $request){
        echo 'hello';

        try{
            $projects = new Project;
            $projects->sujet =$request->input('sujet');
            $projects->filiere =$request->input('filiere');
            $projects->description =$request->input('description');
            $projects->save();
            
            return response()->json([
              'status'=>200,
              'message'=>'Projet a ete bien enregistrer',
            ]);
        }
   
       catch(QueryException $e){
           return response()->json([
           'status'=>1020,
           'message'=>$e->getMessage()
           ]);
        }
    }
}
