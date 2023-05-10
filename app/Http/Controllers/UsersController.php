<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Project;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{

    function GetAllProjects(){
        $projects = Project::all();
        $data = [];
        foreach ($projects as $project) {
            $result = DB::select('select name ,surname,email from users where id = ?', [ $project->id_user]);
            $result = array_map(function ($value) {return (array)$value;}, $result);
            $project->owner_name = $result[0]["name"]." ".$result[0]["surname"];
            $project->owner_email =$result[0]["email"];
            $data[] = $project;
        }
        return response()->json([
            'status' => '200',
            'message'=>'All Projects',
            'data' => $data,
        ]);
    }
    function downloadZipFile(){
        $type ='ConseilPFE.zip';
        $id = DB::select('select id from file where type = ? ',[$type]);
        $id = array_map(function ($value) {return (array)$value;}, $id);
        $id = $id[0]["id"];
        $zipFile = File::findOrFail($id);
        $file = Storage::disk('public')->get($zipFile->path);
        return response($file, 200)->header('Content-Type', 'application/zip')
        ->header('Content-Disposition', 'attachment; filename="' . $zipFile->name . '"');
    }
    
    


}

